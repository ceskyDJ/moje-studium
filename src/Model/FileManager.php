<?php

declare(strict_types = 1);

namespace App\Model;

use App\Entity\PrivateFile;
use App\Entity\User;
use App\Repository\Abstraction\IFileRepository;
use App\Repository\Abstraction\IUserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DOMDocument;
use DOMNode;
use Mammoth\DI\DIClass;
use Tracy\Debugger;
use function filesize;
use function is_dir;
use function is_uploaded_file;
use function json_encode;
use function mkdir;
use function move_uploaded_file;
use function ob_get_clean;
use function ob_start;
use function readfile;
use function realpath;
use function str_replace;
use function strstr;
use function unlink;
use const UPLOAD_ERR_OK;

/**
 * Manager for files
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Model
 */
class FileManager
{
    use DIClass;

    /**
     * Directory for user files
     */
    const DATA_DIR = "data";
    /**
     * Quota for users' folders
     */
    const USER_QUOTA = 50 * 1024 * 1024;

    /**
     * @inject
     */
    private IFileRepository $fileRepository;
    /**
     * @inject
     */
    private IUserRepository $userRepository;
    /**
     * @inject
     */
    private UserManager $userManager;

    /**
     * Renames file
     *
     * @param int $file File's ID
     * @param string $newName New name
     *
     * @return string JSON response
     */
    public function renameFile(int $file, string $newName): string
    {
        if (empty($file) || empty($newName)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($file = $this->fileRepository->getById($file)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Soubor neexistuje",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        if (!($user = $this->userManager->getUser())->isLoggedIn()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Není přihlášen žádný uživatel",
                ]
            );
        }

        if ($file->getOwner()->getId() !== $user->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nevlastníš editovaný soubor",
                ]
            );
        }

        try {
            $this->fileRepository->rename($file->getId(), $newName);
        } catch (UniqueConstraintViolationException $e) {
            // Not unique name in directory
            return json_encode(
                [
                    'success' => false,
                    'message' => "Název souboru musí být unikátní vrámci adresáře (složky)",
                ]
            );
        }

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Returns files from the folder
     *
     * @param string $folder Folder's ID or child's ID with suffix "-parent" (folder is special type of file)
     *
     * @return string JSON response
     */
    public function getFiles(string $folder): string
    {
        if (empty($folder)) {
            return "";
        }

        /**
         * @var $owner \App\Entity\User
         */
        if (!($owner = $this->userManager->getUser())->isLoggedIn()) {
            return "";
        }

        // Child folder's ID has been sent
        // It's in case of getting the parent folder's content
        if (strstr($folder, "-parent")) {
            $childFolder = str_replace("-parent", "", $folder);
            $childFolder = $this->fileRepository->getById((int)$childFolder);

            $parent = $childFolder->getParent();
        } else {
            $parent = $this->fileRepository->getById((int)$folder) ?? null;
        }

        $files = $this->fileRepository->getByOwnerAndParent($owner, $parent);

        $output = [
            'id'    => $parent !== null ? $parent->getId() : "null",
            'files' => [],
        ];
        /**
         * @var \App\Entity\PrivateFile $file
         */
        foreach ($files as $file) {
            $output['files'][] = [
                'id'   => $file->getId(),
                'type' => $file->isFolder() ? "folder" : "file-{$file->getType()}",
                'name' => $file->getName(),
            ];
        }

        return json_encode($output);
    }

    /**
     * Creates new folder
     *
     * @param string $name Folder's name
     * @param string $parent Parent folder's ID or "null" for root folder
     *
     * @return string JSON response
     */
    public function createFolder(string $name, string $parent): string
    {
        if (empty($name) || empty($parent)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        /**
         * @var \App\Entity\User $owner
         */
        if (($owner = $this->userManager->getUser()) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Není přihlášen žádný uživatel",
                ]
            );
        }

        if ($parent === "null" || ($parent = $this->fileRepository->getById((int)$parent)) === null) {
            $parent = null;
        }

        if ($parent !== null && $parent->getOwner()->getId() !== $owner->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nevlastníš nadřazený soubor",
                ]
            );
        }

        if ($parent !== null && $parent->isFolder() === false) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nadřazený soubor není adresář (složka)",
                ]
            );
        }

        if ($this->fileRepository->getByOwnerParentAndName($owner, $parent, $name) !== null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Název musí být v rámci složky unikátní",
                ]
            );
        }

        $id = $this->fileRepository->add($owner, $parent, $name, true)->getId();

        return json_encode(
            [
                'success' => true,
                'id'      => $id,
            ]
        );
    }

    /**
     * Returns folder structure of logged-in user
     *
     * @return string Folder structure in HTML format
     */
    public function getFolderStructure(): string
    {
        $document = new DOMDocument;
        $document->formatOutput = true;

        $this->addFolderItems($document, $document);

        return $document->saveHTML();
    }

    private function addFolderItems(DOMDocument $document, DOMNode $container, ?int $folder = null): void
    {
        /**
         * @var \App\Entity\User $owner
         */
        $owner = $this->userManager->getUser();

        /**
         * @var \App\Entity\PrivateFile $child
         */
        $children = $this->fileRepository->getChildFolders($owner, $folder);
        foreach ($children as $child) {
            $folder = $document->createElement("div");
            $folder->setAttribute("class", "folder _folder");
            $folder->setAttribute("data-id", (string)$child->getId());

            $input = $document->createElement("input");
            $input->setAttribute("type", "radio");
            $input->setAttribute("name", "new-path");
            $input->setAttribute("value", (string)$child->getId());
            $input->setAttribute("class", "_input _move-file-form-parent hide");
            $input->setAttribute("id", "folder-{$child->getId()}");

            $label = $document->createElement("label", $child->getName());
            $label->setAttribute("class", "_label folder-name");
            $label->setAttribute("for", "folder-{$child->getId()}");

            $folder->appendChild($input);
            $folder->appendChild($label);

            $container->appendChild($folder);
            $this->addFolderItems($document, $folder, $child->getId());
        }
    }

    /**
     * Moves file to different location (folder)
     *
     * @param int $file File's ID
     * @param string $parent Parent's ID or "null" for root folder
     *
     * @return string JSON response
     */
    public function moveFile(int $file, string $parent): string
    {
        if (empty($file) || empty($parent)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        /**
         * @var \App\Entity\User $user
         */
        if (($user = $this->userManager->getUser()) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Není přihlášen žádný uživatel",
                ]
            );
        }

        if (($file = $this->fileRepository->getById($file)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Soubor neexistuje",
                ]
            );
        }

        if ($file->getOwner()->getId() !== $user->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nevlastníš přesouvaný soubor",
                ]
            );
        }

        if ($parent === "null" || ($parent = $this->fileRepository->getById((int)$parent)) === null) {
            $parent = null;
        }

        if ($parent !== null && $parent->getOwner()->getId() !== $user->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nevlastníš nadřazený soubor",
                ]
            );
        }

        if ($parent !== null && $parent->isFolder() === false) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nadřazený soubor není adresář (složka)",
                ]
            );
        }

        if ($this->fileRepository->getByOwnerParentAndName($user, $parent, $file->getName()) !== null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "V cílovém adresáři (složce) je již soubor se shodným názvem. Nejprve přesouvaný soubor přejmenuj",
                ]
            );
        }

        $this->fileRepository->move($file->getId(), $parent);

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Saves uploaded file
     *
     * @param array $file File (from $_FILES)
     * @param string $parent Parent folder's ID or null for root
     *
     * @return string JSON response
     */
    public function saveFile(array $file, string $parent): string
    {
        if (empty($file) || empty($parent)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla zaslána všechna data",
                ]
            );
        }

        /**
         * @var \App\Entity\User $owner
         */
        if (($owner = $this->userManager->getUser()) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Není přihlášen žádný uživatel",
                ]
            );
        }

        if ($parent === "null" || ($parent = $this->fileRepository->getById((int)$parent)) === null) {
            $parent = null;
        }

        if ($parent !== null && $parent->getOwner()->getId() !== $owner->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nevlastníš nadřazený soubor",
                ]
            );
        }

        if ($parent !== null && $parent->isFolder() === false) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nadřazený soubor není adresář (složka)",
                ]
            );
        }

        if (empty($file['name'])) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Soubor nemá žádný název",
                ]
            );
        }

        if ($file['size'] === 0) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Soubor je prázdný",
                ]
            );
        }

        if ($file['error'] !== UPLOAD_ERR_OK || is_uploaded_file($file['tmp_name']) === false) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Soubor je chybný",
                ]
            );
        }

        $userFolder = $this->userManager->getUserFolder($owner);
        $newFolderSize = $this->userManager->getDirectorySize($userFolder) + $file['size'];

        if ($userFolder !== null && $newFolderSize > self::USER_QUOTA) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Po nahrání souborů by byla překročena kvóta pro tvé soubory. Nejprve budeš muset něco odstranit.",
                ]
            );
        }

        if ($this->fileRepository->getByOwnerParentAndName($owner, $parent, $file['name']) !== null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Soubor s tímto názvem je již v aktuálním adresáři (složce) obsažen. Pokud se jedná o jiný soubor, přejmenuj ho",
                ]
            );
        }

        $fileId = $this->fileRepository->add($owner, $parent, $file['name'], false)->getId();

        // Add user's folder is doesn't exists
        if ($userFolder === null) {
            $userFolder = $this->makeUserFolder($owner);
        }
        
        move_uploaded_file($file['tmp_name'], "{$userFolder}/{$fileId}");

        return json_encode(
            [
                'success' => true,
                'id'      => $fileId,
            ]
        );
    }

    /**
     * Makes user's folder if doesn't exist
     *
     * @param \App\Entity\User $user User (future owner of the folder)
     *
     * @return string Path to folder
     */
    public function makeUserFolder(User $user): string
    {
        if (($userFolder = $this->userManager->getUserFolder($user)) !== null) {
            return $userFolder;
        }

        $folderPath = __DIR__."/../../".FileManager::DATA_DIR."/{$user->getId()}";
        mkdir($folderPath);

        return realpath($folderPath);
    }

    /**
     * Deletes existing file (or folder)
     *
     * @param int $file File's ID
     *
     * @return string JSON response
     */
    public function deleteFile(int $file): string
    {
        if (empty($file)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        /**
         * @var \App\Entity\User $user
         */
        if (($user = $this->userManager->getUser()) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Není přihlášen žádný uživatel",
                ]
            );
        }

        if (($file = $this->fileRepository->getById($file)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Soubor neexistuje",
                ]
            );
        }

        if ($file->getOwner()->getId() !== $user->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nevlastníš soubor, který chceš odstranit",
                ]
            );
        }

        // File is in filesystem, too (folder isn't)
        if (!$file->isFolder()) {
            unlink("{$this->userManager->getUserFolder($user)}/{$file->getId()}");
        }

        // Folder children have to be deleted, too
        if ($file->isFolder()) {
            $this->deleteChildren($file);
        }

        $this->fileRepository->delete($file->getId());

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Deletes all children recursively
     *
     * @param \App\Entity\PrivateFile $file Start file (parent of deleted items)
     */
    private function deleteChildren(PrivateFile $file): void
    {
        $children = $file->getChildren();
        foreach ($children as $child) {
            if ($child->getChildren()->isEmpty() === false) {
                $this->deleteChildren($child);
            }

            /**
             * @var \App\Entity\User $user
             */
            $user = $this->userManager->getUser();

            unlink("{$this->userManager->getUserFolder($user)}/{$child->getId()}");
        }
    }

    /**
     * Downloads file
     *
     * @param int $file File's ID
     *
     * @return string File's content
     */
    public function downloadFile(int $file): string
    {
        if (empty($file)) {
            return "<script>window.close();</script>";
        }

        // Some is logged in
        /**
         * @var \App\Entity\User $user
         */
        if (($user = $this->userManager->getUser()) === null) {
            return "<script>window.close();</script>";
        }

        // File is existing
        if (($file = $this->fileRepository->getById($file)) === null) {
            return "<script>window.close();</script>";
        }

        // File isn't a folder
        if ($file->isFolder()) {
            return "<script>window.close();</script>";
        }

        // Logged-in user owns the file
        if ($file->getOwner()->getId() !== $user->getId()) {
            return "<script>window.close();</script>";
        }

        $fileAddress = "{$this->userManager->getUserFolder($user)}/{$file->getId()}";

        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"{$file->getName()}\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Content-Length: ".filesize($fileAddress));

        ob_start();
        readfile($fileAddress);

        return ob_get_clean();
    }

    /**
     * Shares file with user's class or specific user
     *
     * @param int $file File's ID
     * @param string $with Who to share with "class" || "schoolmate"
     * @param int $targetUser Target user (schoolmate)
     *
     * @return string JSON response
     */
    public function shareFile(int $file, string $with, ?int $targetUser = null): string
    {
        if (empty($file) || empty($with) || ($with === "schoolmate" && empty($targetUser))) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        /**
         * @var \App\Entity\User $user
         */
        if (($user = $this->userManager->getUser()) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Není přihlášen žádný uživatel",
                ]
            );
        }

        if (($file = $this->fileRepository->getById($file)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Soubor neexistuje",
                ]
            );
        }

        if ($file->isFolder()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Složky není možné sdílet",
                ]
            );
        }

        if ($file->getOwner()->getId() !== $user->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nevlastníš soubor, který chceš sdílet",
                ]
            );
        }

        if ($file->whoIsSharedWith() === "class") {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Soubor je již sdílený s celou třídou. Nemá smysl jej dále sdílet, k většímu počtu uživatelů se již nedostane",
                ]
            );
        }

        // Share with class
        if ($with === "class") {
            // Cancel all shares to schoolmates
            // When it's shared with class, the schoolmates have the access, too
            if ($file->whoIsSharedWith() === "schoolmate") {
                $this->fileRepository->cancelShare($file->getId());
            }

            $this->fileRepository->share($file->getId(), null, $user->getClass());

            return json_encode(
                [
                    'success' => true,
                ]
            );
        }

        // Share with schoolmate
        if ($with === "schoolmate") {
            if (($targetUser = $this->userRepository->getById($targetUser)) === null) {
                return json_encode(
                    [
                        'success' => false,
                        'message' => "Cílový uživatel neexistuje",
                    ]
                );
            }

            if ($targetUser->getId() === $user->getId()) {
                return json_encode(
                    [
                        'success' => false,
                        'message' => "Nemůžeš sdílet soubor se sebou",
                    ]
                );
            }

            $this->fileRepository->share($file->getId(), $targetUser, null);

            return json_encode(
                [
                    'success' => true,
                ]
            );
        }

        return json_encode(
            [
                'success' => false,
                'message' => "Soubor lze sdílet pouze se třídou (\"class\") nebo se spolužákem (\"schoolmate\")",
            ]
        );
    }

    /**
     * Deletes user's files
     *
     * @param int $userId User's ID
     *
     * @return string JSON response
     */
    public function deleteUsersFiles(int $userId): string
    {
        if (empty($userId)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        /**
         * @var \App\Entity\User $user
         */
        if (($user = $this->userRepository->getById($userId)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Uživatel neexistuje",
                ]
            );
        }

        $files = $this->fileRepository->getFilesByOwner($user);
        foreach ($files as $file) {
            unlink("{$this->userManager->getUserFolder($user)}/{$file->getId()}");
        }

        $this->fileRepository->deleteByOwner($user);

        return json_encode(
            [
                'success' => true,
            ]
        );
    }
}
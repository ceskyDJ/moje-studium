<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use App\Model\FileManager;
use App\Model\UserManager;
use App\Repository\Abstraction\IFileRepository;
use App\Repository\Abstraction\IUserRepository;
use Doctrine\Common\Util\Debug;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Exceptions\NonExistingKeyException;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;
use Mammoth\Security\Abstraction\IUserManager;
use Tracy\Debugger;
use const SIGUSR1;

/**
 * Controller for files
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Application
 */
class FilesController extends Controller
{
    use DIClass;

    /**
     * @inject
     */
    private ResponseFactory $responseFactory;
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
     * @inject
     */
    private FileManager $fileManager;

    /**
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        return $this->privateAction($request);
    }

    /**
     * Private (logged-in user's) files
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function privateAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request)->setContentView("private-files")
            ->setTitle("Moje soubory");

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        $response->setDataVar("privateFiles", $this->fileRepository->getByOwnerAndParent($user));
        $response->setDataVar("folderStructure", $this->fileManager->getFolderStructure());
        $response->setDataVar("usersInClass", $this->userRepository->getByClass($user->getClass()));

        return $response;
    }

    /**
     * Shared files (from users or logged-in user'S class)
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function sharedAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request)->setContentView("shared-files")
            ->setTitle("Sdílené soubory");

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        $response->setDataVar("sharedFiles", $this->fileRepository->getSharedByUserOrItsClassWithLimit($user));

        return $response;
    }

    /**
     * Download file
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function downloadAction(Request $request): Response
    {
        Debugger::$showBar = false;

        $data = $request->getParsedUrl()->getData();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->fileManager->downloadFile($data[0]));

        return $response;
    }

    /**
     * Rename existing file
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function renameAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->fileManager->renameFile((int)$data['file'], $data['name']));

        return $response;
    }

    /**
     * Get file from specific folder
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function getFolderContentAjaxAction(Request $request): Response
    {
        $data = $request->getParsedUrl()->getData();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->fileManager->getFiles((string)$data[0]));

        return $response;
    }

    /**
     * Create new folder
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function createFolderAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->fileManager->createFolder($data['name'], $data['parent']));

        return $response;
    }

    /**
     * Move file to different folder
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function moveAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->fileManager->moveFile((int)$data['file'], $data['parent']));

        return $response;
    }

    /**
     * Upload new file
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function uploadAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        try {
            $file = $request->getFileByName("file");
        } catch (NonExistingKeyException $e) {
            $file = [];
        }

        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->fileManager->saveFile($file, $data['parent']));

        return $response;
    }

    /**
     * Delete existing file
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->fileManager->deleteFile((int)$data['file']));

        return $response;
    }

    /**
     * Share file
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function shareAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->fileManager->shareFile((int)$data['file'], $data['with'], (int)$data['schoolmate']));

        return $response;
    }
}
<?php

declare(strict_types = 1);

namespace App\Model;

use App\Repository\Abstraction\INoteRepository;
use App\Repository\Abstraction\IUserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Mammoth\DI\DIClass;
use function json_encode;
use function mb_strlen;

/**
 * Manager for notes
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Model
 */
class NoteManager
{
    use DIClass;

    /**
     * @inject
     */
    private INoteRepository $noteRepository;
    /**
     * @inject
     */
    private IUserRepository $userRepository;
    /**
     * @inject
     */
    private UserManager $userManager;

    /**
     * Takes up note for user
     *
     * @param int $id Shared note's ID
     *
     * @return bool Has it been successful?
     */
    public function takeUp(int $id): bool
    {
        /**
         * @var $user \App\Entity\User
         */
        if (!($user = $this->userManager->getUser())->isLoggedIn()) {
            return false;
        }

        if (($note = $this->noteRepository->getSharedById($id)) === null) {
            return false;
        }

        if (($targetUser = $note->getTargetUser()) !== null) {
            if ($targetUser->getId() !== $user->getId()) {
                return false;
            }
        }

        if (($targetClass = $note->getTargetClass()) !== null) {
            if ($targetClass->getId() !== $user->getClass()->getId()) {
                return false;
            }
        }

        try {
            $this->noteRepository->takeUp($user, $id);
        } catch (UniqueConstraintViolationException $e) {
            // Not unique -> user has already taken up this item
            return false;
        }

        return true;
    }

    /**
     * Adds new note
     *
     * @param string $content Content
     *
     * @return string JSON response
     */
    public function addNote(string $content): string
    {
        if (empty($content)) {
            return json_encode([
                'success' => false,
                'message' => "Nebyla vyplněna všechna pole"
            ]);
        }

        /**
         * @var $user \App\Entity\User
         */
        if (!($user = $this->userManager->getUser())->isLoggedIn()) {
            return json_encode([
                'success' => false,
                'message' => "Není přihlášen žádný uživatel"
            ]);
        }

        $id = $this->noteRepository->add($user, $content);

        return json_encode([
            'success' => true,
            'id' => $id
        ]);
    }

    /**
     * Edits existing note
     *
     * @param int $id ID
     * @param string $content New content
     *
     * @return string JSON response
     */
    public function editNote(int $id, string $content): string
    {
        if (empty($id) || empty($content)) {
            return json_encode([
                'success' => false,
                'message' => "Nebyla vyplněna všechna pole"
            ]);
        }

        /**
         * @var $owner \App\Entity\User
         */
        if (!($owner = $this->userManager->getUser())->isLoggedIn()) {
            return json_encode([
                'success' => false,
                'message' => "Není přihlášen žádný uživatel"
            ]);
        }

        if (($note = $this->noteRepository->getById($id)) === null) {
            return json_encode([
                'success' => false,
                'message' => "Poznámka s daným ID neexistuje"
            ]);
        }

        if ($note->getOwner()->getId() !== $owner->getId()) {
            return json_encode([
                'success' => false,
                'message' => "Poznámka, kterou chceš upravit, není tvoje"
            ]);
        }

        $this->noteRepository->edit($id, $content);

        return json_encode([
            'success' => true
        ]);
    }

    /**
     * Deletes user's note
     *
     * @param int $id Note's ID
     *
     * @return bool Has is been successful?
     */
    public function deleteNote(int $id): bool
    {
        if (empty($id)) {
            return false;
        }

        if (($note = $this->noteRepository->getById($id)) === null) {
            return false;
        }

        if (!($user = $this->userManager->getUser())->isLoggedIn()) {
            return false;
        }

        if ($note->getOwner()->getId() !== $user->getId()) {
            return false;
        }

        $this->noteRepository->delete($id);

        return true;
    }

    /**
     * Shares note with user's class or specific user
     *
     * @param int $note Note's ID
     * @param string $with Who to share with "class" || "schoolmate"
     * @param int $targetUser Target user (schoolmate)
     *
     * @return string JSON response
     */
    public function shareNote(int $note, string $with, ?int $targetUser = null): string
    {
        if (empty($note) || empty($with) || ($with === "schoolmate" && empty($targetUser))) {
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

        if (($note = $this->noteRepository->getById($note)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Poznámka neexistuje",
                ]
            );
        }

        if ($note->getOwner()->getId() !== $user->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nevlastníš poznámku, kterou chceš sdílet",
                ]
            );
        }

        if ($note->whoIsSharedWith() === "class") {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Poznámka je již sdílená s celou třídou. Nemá smysl ji dále sdílet, k většímu počtu uživatelů se již nedostane",
                ]
            );
        }

        // Share with class
        if ($with === "class") {
            // Cancel all shares to schoolmates
            // When it's shared with class, the schoolmates have the access, too
            if ($note->whoIsSharedWith() === "schoolmate") {
                $this->noteRepository->cancelShare($note->getId());
            }

            $this->noteRepository->share($note->getId(), null, $user->getClass());

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
                        'message' => "Nemůžeš sdílet poznámku se sebou",
                    ]
                );
            }

            $this->noteRepository->share($note->getId(), $targetUser, null);

            return json_encode(
                [
                    'success' => true,
                ]
            );
        }

        return json_encode(
            [
                'success' => false,
                'message' => "Poznámku lze sdílet pouze se třídou (\"class\") nebo se spolužákem (\"schoolmate\")",
            ]
        );
    }
}
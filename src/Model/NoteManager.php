<?php

declare(strict_types = 1);

namespace App\Model;

use App\Repository\Abstraction\INoteRepository;
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
}
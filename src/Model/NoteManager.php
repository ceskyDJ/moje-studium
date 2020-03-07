<?php

declare(strict_types = 1);

namespace App\Model;

use App\Repository\Abstraction\INoteRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Mammoth\DI\DIClass;

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
}
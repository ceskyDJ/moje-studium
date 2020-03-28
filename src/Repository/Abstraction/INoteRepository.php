<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\PrivateNote;
use App\Entity\SchoolClass;
use App\Entity\SharedNote;
use App\Entity\User;

/**
 * Repository for notes
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface INoteRepository
{
    /**
     * Returns private notes by owner + his took up notes
     *
     * @param \App\Entity\User $user
     *
     * @return \App\Entity\PrivateReminder[]
     */
    public function getByUser(User $user): array;

    /**
     * Finds private note by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\PrivateNote
     */
    public function getById(int $id): PrivateNote;

    /**
     * Returns $limit notes shared with user or class where the user is
     *
     * @param \App\Entity\User $targetUser
     * @param int|null $limit Maximum count of records
     *
     * @return \App\Entity\SharedNote[]
     */
    public function getSharedByUserOrItsClassWithLimit(User $targetUser, ?int $limit = null): array;

    /**
     * Finds shared note by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\SharedNote
     */
    public function getSharedById(int $id): SharedNote;

    /**
     * Adds new note
     *
     * @param \App\Entity\User $owner
     * @param string $content
     *
     * @return \App\Entity\PrivateNote
     */
    public function add(User $owner, string $content): PrivateNote;

    /**
     * Deletes existing note
     *
     * @param int $id
     */
    public function delete(int $id): void;

    /**
     * Edits note
     *
     * @param int $id
     * @param string $content
     */
    public function edit(int $id, string $content): void;

    /**
     * Shares note
     *
     * @param int $id
     * @param \App\Entity\User|null $targetUser
     * @param \App\Entity\SchoolClass|null $targetClass
     */
    public function share(int $id, ?User $targetUser, ?SchoolClass $targetClass = null): void;

    /**
     * Takes up shared note for user
     *
     * @param \App\Entity\User $user
     * @param int $id
     */
    public function takeUp(User $user, int $id): void;

    /**
     * Cancels all shares of the note
     *
     * @param int $id
     */
    public function cancelShare(int $id): void;
}
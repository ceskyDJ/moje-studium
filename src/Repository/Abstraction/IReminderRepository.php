<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\PrivateReminder;
use App\Entity\SchoolClass;
use App\Entity\SchoolSubject;
use App\Entity\SharedReminder;
use App\Entity\User;
use DateTime;

/**
 * Repository for reminders
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface IReminderRepository
{
    /**
     * Returns private reminders by owner + his took up reminders ordered by date (when property)
     *
     * @param \App\Entity\User $user
     * @param \DateTime|null $from
     * @param \DateTime|null $to
     *
     * @return \App\Entity\PrivateReminder[]
     */
    public function getByUser(User $user, DateTime $from = null, DateTime $to = null): array;

    /**
     * Finds private reminder by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\PrivateReminder
     */
    public function getById(int $id): PrivateReminder;

    /**
     * Returns $limit reminders shared with user or class where the user is
     *
     * @param \App\Entity\User $targetUser
     * @param int|null $limit Maximum count of records
     *
     * @return \App\Entity\SharedReminder[]
     */
    public function getSharedByUserOrItsClassWithLimit(User $targetUser, ?int $limit = null): array;

    /**
     * Finds shared reminder by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\SharedReminder
     */
    public function getSharedById(int $id): SharedReminder;

    /**
     * Adds new reminder
     *
     * @param \App\Entity\User $owner
     * @param string $type
     * @param string $content
     * @param \DateTime $when
     * @param \App\Entity\SchoolSubject $subject
     *
     * @return \App\Entity\PrivateReminder
     */
    public function add(User $owner, string $type, string $content, DateTime $when, SchoolSubject $subject): PrivateReminder;

    /**
     * Deletes existing reminder
     *
     * @param int $id
     */
    public function delete(int $id): void;

    /**
     * Edits reminder
     *
     * @param int $id
     * @param \App\Entity\User $owner
     * @param string $type
     * @param string $content
     * @param \DateTime $when
     * @param \App\Entity\SchoolSubject $subject
     */
    public function edit(
        int $id,
        User $owner,
        string $type,
        string $content,
        DateTime $when,
        SchoolSubject $subject
    ): void;

    /**
     * Shares reminder
     *
     * @param int $id
     * @param \App\Entity\User|null $targetUser
     * @param \App\Entity\SchoolClass|null $targetClass
     */
    public function share(int $id, ?User $targetUser, ?SchoolClass $targetClass = null): void;

    /**
     * Takes up shared reminder for user
     *
     * @param \App\Entity\User $user
     * @param int $id
     */
    public function takeUp(User $user, int $id): void;

    /**
     * Cancels all shares of the reminder
     *
     * @param int $id
     */
    public function cancelShare(int $id): void;
}
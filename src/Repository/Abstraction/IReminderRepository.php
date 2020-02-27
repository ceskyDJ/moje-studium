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
     * Finds private reminder by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\PrivateReminder
     */
    public function getById(int $id): PrivateReminder;

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
     */
    public function add(User $owner, string $type, string $content, DateTime $when, SchoolSubject $subject): void;

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
}
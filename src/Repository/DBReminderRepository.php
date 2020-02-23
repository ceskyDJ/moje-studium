<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ClassGroup;
use App\Entity\SchoolClass;
use App\Entity\SchoolSubject;
use App\Entity\User;
use DateTime;
use Mammoth\Database\DB;

/**
 * Class ReminderRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class ReminderRepository implements Abstraction\IReminderRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(User $owner, string $type, string $content, DateTime $when, SchoolSubject $subject): void
    {
        $this->db->withoutResult(
            "INSERT INTO `user_reminders`(`user_id`, `type`, `content`, `when`, `subject_id`) VALUES(?, ?, ?, ?, ?)",
            $owner->getId(),
            $type,
            $content,
            $when->format("HH:mm"),
            $subject->getId()
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `user_reminders` WHERE `user_reminder_id` = ?", $id);
    }

    /**
     * @inheritDoc
     */
    public function edit(
        int $id,
        User $owner,
        string $type,
        string $content,
        DateTime $when,
        SchoolSubject $subject
    ): void {
        $this->db->withoutResult(
            "UPDATE `user_reminders` SET `user_id` = ?, `type` = ?, `content` = ?, `when` = ?, `subject_id` = ? WHERE `user_reminder_id` = ?",
            $owner->getId(),
            $type,
            $content,
            $when->format("HH:mm"),
            $subject->getId(),
            $id
        );
    }

    /**
     * @inheritDoc
     */
    public function share(int $id, ?User $targetUser, ?SchoolClass $targetClass = null): void
    {
        $targetUserId = ($targetUser !== null ?? $targetUser->getId());
        $targetClassId = ($targetClass !== null ?? $targetClass->getId());

        $this->db->withoutResult(
            "INSERT INTO `shared_reminders`(`user_id`, `class_id`, `user_reminder_id`, `shared`) VALUES(?, ?, ?, NOW())",
            $targetUserId,
            $targetClassId,
            $id
        );
    }

    /**
     * @inheritDoc
     */
    public function takeUp(User $user, int $id): void
    {
        $this->db->withoutResult(
            "INSERT INTO `took_up_shares`(`user_id`,`shared_reminder_id`) VALUES(?, ?)",
            $user,
            $id
        );
    }
}
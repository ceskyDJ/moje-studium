<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ClassGroup;
use App\Entity\SchoolClass;
use App\Entity\User;
use Mammoth\Database\DB;

/**
 * Class NoteRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class NoteRepository implements Abstraction\INoteRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(User $owner, string $content): void
    {
        $this->db->withoutResult(
            "INSERT INTO `user_notes`(`user_id`, `content`) VALUES(?, ?)",
            $owner->getId(),
            $content
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `user_notes` WHERE user_notes.`user_note_id` = ?", $id);
    }

    /**
     * @inheritDoc
     */
    public function edit(int $id, string $content): void
    {
        $this->db->withoutResult("UPDATE `user_notes` SET `content` = ? WHERE `user_note_id` = ?", $content, $id);
    }

    /**
     * @inheritDoc
     */
    public function share(int $id, ?User $targetUser, ?SchoolClass $targetClass = null): void
    {
        $targetUserId = ($targetUser !== null ?? $targetUser->getId());
        $targetClassId = ($targetClass !== null ?? $targetClass->getId());

        $this->db->withoutResult(
            "INSERT INTO `shared_notes`(`user_id`, `class_id`, `user_note_id`, `shared`) VALUES(?, ?, ?, NOW())",
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
            "INSERT INTO `took_up_shares`(`user_id`, `shared_note_id`) VALUES(?, ?)",
            $user->getId(),
            $id
        );
    }
}
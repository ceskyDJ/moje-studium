<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ClassGroup;
use App\Entity\User;
use Mammoth\Database\DB;

/**
 * Class FileRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class FileRepository implements Abstraction\IFileRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(User $owner, string $name, string $path, bool $folder): void
    {
        $this->db->withoutResult(
            "INSERT INTO `user_files`(`user_id`, `name`, `path`, `folder`) VALUES(?, ?, ?, ?)",
            $owner->getId(),
            $name,
            $path,
            $folder
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `user_files` WHERE `user_file_id` = ?", $id);
    }

    /**
     * @inheritDoc
     */
    public function edit(int $id, string $name, string $path): void
    {
        $this->db->withoutResult(
            "UPDATE `user_files` SET `name` = ?, `path` = ? WHERE `user_file_id` = ?",
            $name,
            $path,
            $id
        );
    }

    /**
     * @inheritDoc
     */
    public function share(int $id, ?User $targetUser, ?ClassGroup $targetGroup = null): void
    {
        $targetUserId = ($targetUser !== null ?? $targetUser->getId());
        $targetGroupId = ($targetGroup !== null ?? $targetGroup->getId());

        $this->db->withoutResult(
            "INSERT INTO `shared_files`(`user_id`, `group_id`, `user_file_id`, `shared`) VALUES(?, ?, ?, NOW())",
            $targetUserId,
            $targetGroupId,
            $id
        );
    }
}
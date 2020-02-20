<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Rank;
use App\Entity\SchoolClass;
use Mammoth\Database\DB;

/**
 * Class UserRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class UserRepository implements Abstraction\IUserRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(
        string $username,
        string $password,
        Rank $rank,
        string $firstName,
        string $lastName,
        string $email
    ): void {
        $this->db->withoutResult(
            "INSERT INTO `users`(`username`, `password`, `rank_id`) VALUES(?, ?, ?)",
            $username,
            $password,
            $rank->getId()
        );

        $id = $this->db->getLastId();

        $this->db->withoutResult(
            "INSERT INTO `user_data`(`user_id`, `first_name`, `last_name`, `email`) VALUES(?, ?, ?, ?)",
            $id,
            $firstName,
            $lastName,
            $email
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `users` WHERE `user_id` = ?", $id);
    }

    /**
     * @inheritDoc
     */
    public function edit(
        int $id,
        string $username,
        string $password,
        Rank $rank,
        ?SchoolClass $class,
        string $firstName,
        string $lastName,
        string $email
    ): void {
        $classId = ($class !== null ?? $class->getId());

        $this->db->withoutResult(
            "
                UPDATE `users` SET users.`username` = ?, users.`password` = ?, users.`rank_id` = ?, users.`class_id` = ? WHERE users.`user_id` = ?;
                UPDATE `user_data` SET `first_name` = ?, `last_name` = ?, `email` = ? WHERE `user_id` = ?
            ",
            $username, $password, $rank->getId(), $class->getId(), $classId, $id, $firstName, $lastName, $email, $id);
    }
}
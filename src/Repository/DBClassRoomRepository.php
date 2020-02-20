<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\SchoolClass;
use Mammoth\Database\DB;

/**
 * Class ClassRoomRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class ClassRoomRepository implements Abstraction\IClassRoomRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(SchoolClass $class, string $name, ?string $description): void
    {
        $this->db->withoutResult(
            "INSERT INTO `classrooms`(`class_id`, `name`, `description`) VALUES(?, ?, ?)",
            $class->getId(),
            $name,
            $description
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `classrooms` WHERE `classroom_id` = ?", $id);
    }
}
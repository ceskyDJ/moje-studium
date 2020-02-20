<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\SchoolClass;
use Mammoth\Database\DB;

/**
 * Class TeacherRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class TeacherRepository implements Abstraction\ITeacherRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(SchoolClass $class, string $firstName, string $lastName, string $degreeBefore, string $degreeAfter, string $shortcut): void
    {
        $this->db->withoutResult(
            "INSERT INTO `teachers`(`class_id`, `first_name`, `last_name`, `degree_before`, `degree_after`, `shortcut`) VALUES(?, ?, ?, ?, ?, ?)",
            $class,
            $firstName,
            $lastName,
            $degreeBefore,
            $degreeAfter,
            $shortcut
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `teachers` WHERE `teacher_id` = ?", $id);
    }
}
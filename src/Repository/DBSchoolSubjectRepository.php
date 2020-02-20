<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\SchoolClass;
use Mammoth\Database\DB;

/**
 * Class SchoolSubjectRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class SchoolSubjectRepository implements Abstraction\ISchoolSubjectRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(SchoolClass $class, string $name, string $shortcut): void
    {
        $this->db->withoutResult(
            "INSERT INTO `subjects`(`class_id`, `name`, `shortcut`) VALUES(?, ?, ?)",
            $class->getId(),
            $name,
            $shortcut
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `subjects` WHERE `subject_id` = ?", $id);
    }
}
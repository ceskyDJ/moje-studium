<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\School;
use Mammoth\Database\DB;

/**
 * Class SchoolClassRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class SchoolClassRepository implements Abstraction\ISchoolClassRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(string $name, int $startYear, int $studyLength, School $school): void
    {
        $this->db->withoutResult(
            "INSERT INTO `classes`(`name`, `start_year`, `study_length`, `school_id`) VALUES(?, ?, ?, ?)",
            $name,
            $startYear,
            $studyLength,
            $school->getId()
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `classes` WHERE `class_id` = ?", $id);
    }

    /**
     * @inheritDoc
     */
    public function edit(int $id, string $name, int $startYear, int $studyLength, School $school): void
    {
        $this->db->withoutResult(
            "UPDATE `classes` SET `name` = ?, `start_year` = ?, `study_length` = ?, `school_id` = ? WHERE `class_id` = ?",
            $name,
            $startYear,
            $studyLength,
            $school->getId(),
            $id
        );
    }
}
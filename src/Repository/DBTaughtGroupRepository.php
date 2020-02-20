<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ClassGroup;
use App\Entity\SchoolSubject;
use App\Entity\Teacher;
use Mammoth\Database\DB;

/**
 * Class TaughtGroupRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class TaughtGroupRepository implements Abstraction\ITaughtGroupRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(ClassGroup $group, SchoolSubject $subject, Teacher $teacher): void
    {
        $this->db->withoutResult(
            "INSERT INTO `taught_groups`(`group_id`, `subject_id`, `teacher_id`) VALUES(?, ?, ?)",
            $group->getId(),
            $subject->getId(),
            $teacher->getId()
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `taught_groups` WHERE `taught_group_id` = ?", $id);
    }
}
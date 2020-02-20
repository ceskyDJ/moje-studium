<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Classroom;
use App\Entity\TaughtGroup;
use DateTime;
use Mammoth\Database\DB;

/**
 * Class LessonRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class LessonRepository implements Abstraction\ILessonRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(
        int $timetablePosition,
        DateTime $from,
        DateTime $to,
        string $dayOfWeek,
        Classroom $classroom,
        TaughtGroup $taughtGroup
    ): void {
        $this->db->withoutResult(
            "INSERT INTO `lessons`(`position_number`, `from`, `to`, `day_of_week`, `classroom_id`, `taught_group_id`) VALUES(?, ?, ?, ?, ?, ?)",
            $timetablePosition,
            $from->format("HH:mm"),
            $to->format("HH:mm"),
            $dayOfWeek,
            $classroom->getId(),
            $taughtGroup->getId()
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `lessons` WHERE `lesson_id`", $id);
    }

    /**
     * @inheritDoc
     */
    public function edit(
        int $id,
        int $timetablePosition,
        DateTime $from,
        DateTime $to,
        string $dayOfWeek,
        Classroom $classroom,
        TaughtGroup $taughtGroup
    ): void {
        $this->db->withoutResult(
            "UPDATE `lessons` SET `position_number` = ?, `from` = ?, `to` = ?, `day_of_week` = ?, `classroom_id` = ?, `taught_group_id` = ? WHERE lessons.`lesson_id` = ?",
            $timetablePosition,
            $from->format("HH:mm"),
            $to->format("HH:mm"),
            $dayOfWeek,
            $classroom->getId(),
            $taughtGroup->getId()
        );
    }
}
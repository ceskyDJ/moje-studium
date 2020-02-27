<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\Classroom;
use App\Entity\Lesson;
use App\Entity\TaughtGroup;
use DateTime;

/**
 * Repository for lessons
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface ILessonRepository
{
    /**
     * Finds lesson by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\Lesson
     */
    public function getById(int $id): Lesson;

    /**
     * Adds new lesson
     *
     * @param int $timetablePosition
     * @param \DateTime $from
     * @param \DateTime $to
     * @param int $dayOfWeek
     * @param \App\Entity\Classroom $classroom
     * @param \App\Entity\TaughtGroup $taughtGroup
     */
    public function add(
        int $timetablePosition,
        DateTime $from,
        DateTime $to,
        int $dayOfWeek,
        Classroom $classroom,
        TaughtGroup $taughtGroup
    ): void;

    /**
     * Deletes existing lesson
     *
     * @param int $id
     */
    public function delete(int $id): void;

    /**
     * Edits lesson
     *
     * @param int $id
     * @param int $timetablePosition
     * @param \DateTime $from
     * @param \DateTime $to
     * @param int $dayOfWeek
     * @param \App\Entity\Classroom $classroom
     * @param \App\Entity\TaughtGroup $taughtGroup
     */
    public function edit(
        int $id,
        int $timetablePosition,
        DateTime $from,
        DateTime $to,
        int $dayOfWeek,
        Classroom $classroom,
        TaughtGroup $taughtGroup
    ): void;
}
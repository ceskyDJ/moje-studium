<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;

/**
 * Lesson
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class Lesson
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var int Position in timetable
     */
    private int $timetablePosition;
    /**
     * @var \DateTime When the lesson starts
     */
    private DateTime $from;
    /**
     * @var \DateTime When the lesson ends
     */
    private DateTime $to;
    /**
     * @var string Day of week - in English in shortcut form (for ex. mon, tue)!
     */
    private string $dayOfWeek;
    /**
     * @var \App\Entity\Classroom Classroom
     */
    private Classroom $classroom;
    /**
     * @var \App\Entity\TaughtGroup Connection of teacher, subject and class group
     */
    private TaughtGroup $taughtGroup;

    /**
     * Lesson constructor
     *
     * @param int $id
     * @param int $timetablePosition
     * @param \DateTime $from
     * @param \DateTime $to
     * @param string $dayOfWeek
     * @param \App\Entity\Classroom $classroom
     * @param \App\Entity\TaughtGroup $taughtGroup
     */
    public function __construct(
        int $id,
        int $timetablePosition,
        DateTime $from,
        DateTime $to,
        string $dayOfWeek,
        Classroom $classroom,
        TaughtGroup $taughtGroup
    ) {
        $this->id = $id;
        $this->timetablePosition = $timetablePosition;
        $this->from = $from;
        $this->to = $to;
        $this->dayOfWeek = $dayOfWeek;
        $this->classroom = $classroom;
        $this->taughtGroup = $taughtGroup;
    }

    /**
     * Getter for id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Fluent setter for id
     *
     * @param int $id
     *
     * @return Lesson
     */
    public function setId(int $id): Lesson
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for timetablePosition
     *
     * @return int
     */
    public function getTimetablePosition(): int
    {
        return $this->timetablePosition;
    }

    /**
     * Fluent setter for timetablePosition
     *
     * @param int $timetablePosition
     *
     * @return Lesson
     */
    public function setTimetablePosition(int $timetablePosition): Lesson
    {
        $this->timetablePosition = $timetablePosition;

        return $this;
    }

    /**
     * Getter for from
     *
     * @return \DateTime
     */
    public function getFrom(): DateTime
    {
        return $this->from;
    }

    /**
     * Fluent setter for from
     *
     * @param \DateTime $from
     *
     * @return Lesson
     */
    public function setFrom(DateTime $from): Lesson
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Getter for to
     *
     * @return \DateTime
     */
    public function getTo(): DateTime
    {
        return $this->to;
    }

    /**
     * Fluent setter for to
     *
     * @param \DateTime $to
     *
     * @return Lesson
     */
    public function setTo(DateTime $to): Lesson
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Getter for dayOfWeek
     *
     * @return string
     */
    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }

    /**
     * Fluent setter for dayOfWeek
     *
     * @param string $dayOfWeek
     *
     * @return Lesson
     */
    public function setDayOfWeek(string $dayOfWeek): Lesson
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    /**
     * Getter for classroom
     *
     * @return \App\Entity\Classroom
     */
    public function getClassroom(): Classroom
    {
        return $this->classroom;
    }

    /**
     * Fluent setter for classroom
     *
     * @param \App\Entity\Classroom $classroom
     *
     * @return Lesson
     */
    public function setClassroom(Classroom $classroom): Lesson
    {
        $this->classroom = $classroom;

        return $this;
    }

    /**
     * Getter for taughtGroup
     *
     * @return \App\Entity\TaughtGroup
     */
    public function getTaughtGroup(): TaughtGroup
    {
        return $this->taughtGroup;
    }

    /**
     * Fluent setter for taughtGroup
     *
     * @param \App\Entity\TaughtGroup $taughtGroup
     *
     * @return Lesson
     */
    public function setTaughtGroup(TaughtGroup $taughtGroup): Lesson
    {
        $this->taughtGroup = $taughtGroup;

        return $this;
    }
}
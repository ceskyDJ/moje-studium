<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Lesson
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="lessons", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_lessons_group_position_time_day", columns={"taught_group_id", "position_number", "from", "to", "day_of_week"})
 * })
 * @ORM\Entity
 */
class Lesson
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @ORM\Column(name="lesson_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private ?int $id = null;
    /**
     * @var int Position in timetable
     * @ORM\Column(name="position_number", type="smallint", length=3, nullable=false, options={ "unsigned": true })
     */
    private int $timetablePosition;
    /**
     * @var \DateTime When the lesson starts
     * @ORM\Column(name="from", type="time", nullable=false, options={  })
     */
    private DateTime $from;
    /**
     * @var \DateTime When the lesson ends
     * @ORM\Column(name="to", type="time", nullable=false, options={  })
     */
    private DateTime $to;
    /**
     * @var int Day of week - 0 = Monday, 1 = Tuesday, ..., 6 = Sunday
     * @ORM\Column(name="day_of_week", type="smallint", length=1, nullable=false, options={  })
     */
    private int $dayOfWeek;
    /**
     * @ORM\ManyToOne(targetEntity="Classroom", inversedBy="lessons")
     * @ORM\JoinColumn(name="classroom_id", referencedColumnName="classroom_id", nullable=false)
     * @var \App\Entity\Classroom Classroom
     */
    private Classroom $classroom;
    /**
     * @ORM\ManyToOne(targetEntity="TaughtGroup", inversedBy="lessons")
     * @ORM\JoinColumn(name="taught_group_id", referencedColumnName="taught_group_id", nullable=false)
     * @var \App\Entity\TaughtGroup Connection of teacher, subject and class group
     */
    private TaughtGroup $taughtGroup;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Lesson
    {
        $this->id = $id;

        return $this;
    }

    public function getTimetablePosition(): int
    {
        return $this->timetablePosition;
    }

    public function setTimetablePosition(int $timetablePosition): Lesson
    {
        $this->timetablePosition = $timetablePosition;

        return $this;
    }

    public function getFrom(): DateTime
    {
        return $this->from;
    }

    public function setFrom(DateTime $from): Lesson
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): DateTime
    {
        return $this->to;
    }

    public function setTo(DateTime $to): Lesson
    {
        $this->to = $to;

        return $this;
    }

    public function getDayOfWeek(): int
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(int $dayOfWeek): Lesson
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function getClassroom(): Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(Classroom $classroom): Lesson
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getTaughtGroup(): TaughtGroup
    {
        return $this->taughtGroup;
    }

    public function setTaughtGroup(TaughtGroup $taughtGroup): Lesson
    {
        $this->taughtGroup = $taughtGroup;

        return $this;
    }
}
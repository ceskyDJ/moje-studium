<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Classroom;
use App\Entity\Lesson;
use App\Entity\TaughtGroup;
use DateTime;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for lessons
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBLessonRepository implements Abstraction\ILessonRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): Lesson
    {
        /**
         * @var $lesson Lesson
         */
        $lesson = $this->em->find(Lesson::class, $id);

        return $lesson;
    }

    /**
     * @inheritDoc
     */
    public function add(
        int $timetablePosition,
        DateTime $from,
        DateTime $to,
        int $dayOfWeek,
        Classroom $classroom,
        TaughtGroup $taughtGroup
    ): void {
        $lesson = new Lesson;
        $lesson->setTimetablePosition($timetablePosition)
            ->setFrom($from)
            ->setTo($to)
            ->setDayOfWeek($dayOfWeek)
            ->setClassroom($classroom)
            ->setTaughtGroup($taughtGroup);

        $this->em->persist($lesson);
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->em->remove($this->getById($id));
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function edit(
        int $id,
        int $timetablePosition,
        DateTime $from,
        DateTime $to,
        int $dayOfWeek,
        Classroom $classroom,
        TaughtGroup $taughtGroup
    ): void {
        $lesson = $this->getById($id);
        $lesson->setTimetablePosition($timetablePosition)
            ->setFrom($from)
            ->setTo($to)
            ->setDayOfWeek($dayOfWeek)
            ->setClassroom($classroom)
            ->setTaughtGroup($taughtGroup);

        $this->em->flush();
    }
}
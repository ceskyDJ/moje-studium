<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\SchoolClass;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for teachers
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBTeacherRepository implements Abstraction\ITeacherRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): Teacher
    {
        /**
         * @var $teacher Teacher
         */
        $teacher = $this->em->find(Teacher::class, $id);

        return $teacher;
    }

    /**
     * @inheritDoc
     */
    public function add(
        SchoolClass $class,
        string $firstName,
        string $lastName,
        string $degreeBefore,
        string $degreeAfter,
        string $shortcut
    ): Teacher {
        $teacher = new Teacher;
        $teacher->setClass($class)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setDegreeBefore($degreeBefore)
            ->setDegreeAfter($degreeAfter)
            ->setShortcut($shortcut);

        $this->em->persist($teacher);
        $this->em->flush();

        return $teacher;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->em->remove($this->getById($id));
        $this->em->flush();
    }
}
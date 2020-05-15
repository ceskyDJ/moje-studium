<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Classroom;
use App\Entity\SchoolClass;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for classrooms
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBClassroomRepository implements Abstraction\IClassroomRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function add(SchoolClass $class, string $name, ?string $description): Classroom
    {
        $classroom = new Classroom;
        $classroom->setClass($class)->setName($name)->setDescription($description);

        $this->em->persist($classroom);
        $this->em->flush();

        return $classroom;
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
    public function getById(int $id): Classroom
    {
        /**
         * @var $classroom Classroom
         */
        $classroom = $this->em->find(Classroom::class, $id);

        return $classroom;
    }
}
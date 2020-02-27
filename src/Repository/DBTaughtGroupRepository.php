<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ClassGroup;
use App\Entity\SchoolSubject;
use App\Entity\TaughtGroup;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Class TaughtGroupRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class TaughtGroupRepository implements Abstraction\ITaughtGroupRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): TaughtGroup
    {
        /**
         * @var $taughtGroup TaughtGroup
         */
        $taughtGroup = $this->em->find(TaughtGroup::class, $id);

        return $taughtGroup;
    }

    /**
     * @inheritDoc
     */
    public function add(ClassGroup $group, SchoolSubject $subject, Teacher $teacher): void
    {
        $taughtGroup = new TaughtGroup;
        $taughtGroup->setGroup($group)->setSubject($subject)->setTeacher($teacher);

        $this->em->persist($taughtGroup);
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
}
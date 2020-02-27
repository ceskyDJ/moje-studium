<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\SchoolClass;
use App\Entity\SchoolSubject;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Class SchoolSubjectRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class SchoolSubjectRepository implements Abstraction\ISchoolSubjectRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): SchoolSubject
    {
        /**
         * @var $subject SchoolSubject
         */
        $subject = $this->em->find(SchoolSubject::class, $id);

        return $subject;
    }

    /**
     * @inheritDoc
     */
    public function add(SchoolClass $class, string $name, string $shortcut): void
    {
        $subject = new SchoolSubject;
        $subject->setClass($class)->setName($name)->setShortcut($shortcut);

        $this->em->persist($subject);
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
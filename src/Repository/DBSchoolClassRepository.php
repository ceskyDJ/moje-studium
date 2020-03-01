<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\School;
use App\Entity\SchoolClass;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for school classes
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBSchoolClassRepository implements Abstraction\ISchoolClassRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): SchoolClass
    {
        /**
         * @var $class SchoolClass
         */
        $class = $this->em->find(SchoolClass::class, $id);

        return $class;
    }

    /**
     * @inheritDoc
     */
    public function getByNameSchoolAndStartYear(string $name, School $school, int $startYear): ?SchoolClass
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT c FROM App\Entity\SchoolClass c WHERE c.name = :name AND c.school = :school AND c.startYear = :startYear
        ");
        $query->setParameter("name", $name);
        $query->setParameter("school", $school);
        $query->setParameter("startYear", $startYear);

        return $query->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function add(string $name, int $startYear, int $studyLength, School $school): SchoolClass
    {
        $class = new SchoolClass;
        $class->setName($name)->setStartYear($startYear)->setStudyLength($studyLength)->setSchool($school);

        $this->em->persist($class);
        $this->em->flush();

        return $class;
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
    public function edit(int $id, string $name, int $startYear, int $studyLength, School $school): void
    {
        $class = $this->getById($id);
        $class->setName($name)->setStartYear($startYear)->setStudyLength($studyLength)->setSchool($school);

        $this->em->flush();
    }
}
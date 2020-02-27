<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Country;
use App\Entity\Region;
use App\Entity\School;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for schools
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBSchoolRepository implements Abstraction\ISchoolRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): School
    {
        /**
         * @var $school School
         */
        $school = $this->em->find(School::class, $id);

        return $school;
    }

    /**
     * @inheritDoc
     */
    public function add(string $name, Country $country, ?Region $region, string $street, string $city): void
    {
        $school = new School;
        $school->setName($name)->setCountry($country)->setRegion($region)->setStreet($street)->setCity($city);

        $this->em->persist($school);
        $this->em->flush();
    }
}
<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Country;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Class CountryRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class CountryRepository implements Abstraction\ICountryRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): Country
    {
        /**
         * @var $country Country
         */
        $country = $this->em->find(Country::class, $id);

        return $country;
    }
}
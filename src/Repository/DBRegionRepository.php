<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Region;
use Doctrine\ORM\EntityManager;
use Mammoth\Database\DB;
use Mammoth\DI\DIClass;

/**
 * Class RegionRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class RegionRepository implements Abstraction\IRegionRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): Region
    {
        /**
         * @var $region Region
         */
        $region = $this->em->find(Region::class, $id);

        return $region;
    }
}
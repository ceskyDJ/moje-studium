<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Rank;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for ranks
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBRankRepository implements Abstraction\IRankRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): Rank
    {
        /**
         * @var $rank Rank
         */
        $rank = $this->em->find(Rank::class, $id);

        return $rank;
    }
}
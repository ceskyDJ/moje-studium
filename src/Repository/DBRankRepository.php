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

    /**
     * @inheritDoc
     */
    public function getByName(string $name): ?Rank
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT r FROM App\Entity\Rank r WHERE r.name = :name
        ");
        $query->setParameter("name", $name);

        return $query->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function getDefaultForUsers(): Rank
    {
        return $this->getById(2);
    }
}
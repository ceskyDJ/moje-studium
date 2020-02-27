<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ProfileIcon;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for profile icons
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBProfileIconRepository implements Abstraction\IProfileIconRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): ProfileIcon
    {
        /**
         * @var $profileIcon ProfileIcon
         */
        $profileIcon = $this->em->find(ProfileIcon::class, $id);

        return $profileIcon;
    }
}
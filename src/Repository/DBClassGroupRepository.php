<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ClassGroup;
use App\Entity\SchoolClass;
use App\Repository\Abstraction\IClassGroupRepository;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for class groups
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBClassGroupRepository implements IClassGroupRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): ClassGroup
    {
        /**
         * @var $group ClassGroup
         */
        $group = $this->em->find(ClassGroup::class, $id);

        return $group;
    }

    /**
     * @inheritDoc
     */
    public function add(string $name, SchoolClass $class): void
    {
        $group = new ClassGroup;
        $group->setName($name)->setClass($class);

        $this->em->persist($group);
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
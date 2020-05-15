<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ClassGroup;
use App\Entity\SchoolClass;
use App\Entity\User;
use App\Repository\Abstraction\IClassGroupRepository;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;
use Tracy\Debugger;

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
    public function getByClass(SchoolClass $class): array
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT g, CASE WHEN g.name = 'CLASS' THEN 1 ELSE 0 END AS HIDDEN sorting
            FROM App\Entity\ClassGroup g
            WHERE g.class = :class
            ORDER BY sorting DESC, g.id
        ");
        $query->setParameter("class", $class);

        return $query->getResult();
    }

    /**
     * @inheritDoc
     */
    public function getByClassAndName(SchoolClass $class, string $name): ?ClassGroup
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT g
            FROM App\Entity\ClassGroup g
            WHERE g.class = :class AND g.name = :name
        ");
        $query->setParameter("class", $class);
        $query->setParameter("name", $name);

        return $query->getOneOrNullResult();
    }

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
    public function add(string $name, SchoolClass $class): ClassGroup
    {
        $group = new ClassGroup;
        $group->setName($name)->setClass($class);

        $this->em->persist($group);
        $this->em->flush();

        return $group;
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
    public function addUser(int $id, User $user): void
    {
        $group = $this->getById($id);

        $group->addUser($user);

        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function addUserToWholeClassGroup(User $user, SchoolClass $class): void
    {
        $group = $this->getByClassAndName($class, self::WHOLE_CLASS_GROUP);

        $this->addUser($group->getId(), $user);
    }

    /**
     * @inheritDoc
     */
    public function deleteUser(int $id, User $user): void
    {
        $group = $this->getById($id);

        $group->removeUser($user);

        $this->em->flush();
    }
}
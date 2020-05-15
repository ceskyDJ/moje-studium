<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ClassSelectionRequest;
use App\Entity\SchoolClass;
use App\Entity\User;
use App\Repository\Abstraction\IClassSelectionRequestRepository;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for class selection requests
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBClassSelectionRequestRepository implements IClassSelectionRequestRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): ClassSelectionRequest
    {
        /**
         * @var $selectionRequest ClassSelectionRequest
         */
        $selectionRequest = $this->em->find(ClassSelectionRequest::class, $id);

        return $selectionRequest;
    }

    /**
     * @inheritDoc
     */
    public function getByClass(SchoolClass $class, bool $onlyActive): array
    {
        $query = $this->em->createQuery(
        /** @lang DQL */ "
            SELECT r FROM App\Entity\ClassSelectionRequest r WHERE r.class = :class AND (:active = false OR r.approved IS NULL)
        "
        );
        $query->setParameter("class", $class);
        $query->setParameter("active", $onlyActive);

        return $query->getResult();
    }

    /**
     * @inheritDoc
     */
    public function add(User $user, SchoolClass $class): ClassSelectionRequest
    {
        $selectionRequest = new ClassSelectionRequest;
        $selectionRequest->setUser($user)->setClass($class);

        $this->em->persist($selectionRequest);
        $this->em->flush();

        return $selectionRequest;
    }

    /**
     * @inheritDoc
     */
    public function close(int $id, bool $approved): void
    {
        $selectionRequest = $this->getById($id);

        $selectionRequest->setApproved($approved);

        $this->em->flush();
    }
}
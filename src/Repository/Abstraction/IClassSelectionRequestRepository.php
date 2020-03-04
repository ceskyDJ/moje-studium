<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\ClassSelectionRequest;
use App\Entity\SchoolClass;
use App\Entity\User;

/**
 * Repository for class selection requests
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface IClassSelectionRequestRepository
{
    /**
     * Finds class selection request by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\ClassSelectionRequest
     */
    public function getById(int $id): ClassSelectionRequest;

    /**
     * Adds (resp. creates) new class selection request
     *
     * @param \App\Entity\User $user
     * @param \App\Entity\SchoolClass $class
     *
     * @return \App\Entity\ClassSelectionRequest
     */
    public function add(User $user, SchoolClass $class): ClassSelectionRequest;
}
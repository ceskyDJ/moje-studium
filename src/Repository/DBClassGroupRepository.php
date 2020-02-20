<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\SchoolClass;
use App\Repository\Abstraction\IClassGroupRepository;
use Mammoth\Database\DB;

/**
 * Repository for class groups
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class ClassGroupRepository implements IClassGroupRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(string $name, SchoolClass $class): void
    {
        $this->db->withoutResult("INSERT INTO `groups`(`name`, `class_id`) VALUES(?, ?)", $name, $class->getId());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `groups` WHERE `group_id` = ?", $id);
    }
}
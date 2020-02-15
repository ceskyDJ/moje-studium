<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Rank (permission group) for user
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class Rank
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var string Short name (for printing)
     */
    private string $name;
    /**
     * @var int Permission level (how many permissions contains the rank)
     */
    private int $permissionLevel;

    /**
     * Rank constructor
     *
     * @param int $id
     * @param string $name
     * @param int $permissionLevel
     */
    public function __construct(int $id, string $name, int $permissionLevel)
    {
        $this->id = $id;
        $this->name = $name;
        $this->permissionLevel = $permissionLevel;
    }

    /**
     * Getter for id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Fluent setter for id
     *
     * @param int $id
     *
     * @return Rank
     */
    public function setId(int $id): Rank
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Fluent setter for name
     *
     * @param string $name
     *
     * @return Rank
     */
    public function setName(string $name): Rank
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for permissionLevel
     *
     * @return int
     */
    public function getPermissionLevel(): int
    {
        return $this->permissionLevel;
    }

    /**
     * Fluent setter for permissionLevel
     *
     * @param int $permissionLevel
     *
     * @return Rank
     */
    public function setPermissionLevel(int $permissionLevel): Rank
    {
        $this->permissionLevel = $permissionLevel;

        return $this;
    }
}
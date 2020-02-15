<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Profile icon available to use by user as profile image
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class ProfileIcon
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var string Name of the class to use (for ex. duck-profile-i) in profile image
     */
    private string $name;

    /**
     * ProfileIcon constructor
     *
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
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
     * @return ProfileIcon
     */
    public function setId(int $id): ProfileIcon
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
     * @return ProfileIcon
     */
    public function setName(string $name): ProfileIcon
    {
        $this->name = $name;

        return $this;
    }
}
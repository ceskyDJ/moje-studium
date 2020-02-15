<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Country (for ex. Czech republic)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class Country
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var string Official name
     */
    private string $name;
    /**
     * @var string Official code (for ex. cz for Czech republic)
     */
    private string $code;

    /**
     * Country constructor
     *
     * @param int $id
     * @param string $name
     * @param string $code
     */
    public function __construct(int $id, string $name, string $code)
    {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
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
     * @return Country
     */
    public function setId(int $id): Country
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
     * @return Country
     */
    public function setName(string $name): Country
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Fluent setter for code
     *
     * @param string $code
     *
     * @return Country
     */
    public function setCode(string $code): Country
    {
        $this->code = $code;

        return $this;
    }
}
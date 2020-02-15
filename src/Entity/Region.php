<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Region (in US meanings state)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class Region
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var string Official name (for ex. Pardubický kraj in Czech republic)
     */
    private string $name;
    /**
     * @var \App\Entity\Country Country, where the region is
     */
    private Country $country;

    /**
     * Region constructor
     *
     * @param int $id
     * @param string $name
     * @param \App\Entity\Country $country
     */
    public function __construct(int $id, string $name, Country $country)
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
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
     * @return Region
     */
    public function setId(int $id): Region
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
     * @return Region
     */
    public function setName(string $name): Region
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for country
     *
     * @return \App\Entity\Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * Fluent setter for country
     *
     * @param \App\Entity\Country $country
     *
     * @return Region
     */
    public function setCountry(Country $country): Region
    {
        $this->country = $country;

        return $this;
    }
}
<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * School
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class School
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
     * @var \App\Entity\Country Address: country
     */
    private Country $country;
    /**
     * @var \App\Entity\Region|null Address: region
     */
    private ?Region $region;
    /**
     * @var string Address: street (with house number)
     */
    private string $street;
    /**
     * @var string Address: city
     */
    private string $city;

    /**
     * School constructor
     *
     * @param int $id
     * @param string $name
     * @param \App\Entity\Country $country
     * @param \App\Entity\Region|null $region
     * @param string $street
     * @param string $city
     */
    public function __construct(
        int $id,
        string $name,
        Country $country,
        ?Region $region,
        string $street,
        string $city
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->region = $region;
        $this->street = $street;
        $this->city = $city;
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
     * @return School
     */
    public function setId(int $id): School
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
     * @return School
     */
    public function setName(string $name): School
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for country
     *
     * @return \App\Entity\Country
     */
    public function getCountry(): \App\Entity\Country
    {
        return $this->country;
    }

    /**
     * Fluent setter for country
     *
     * @param \App\Entity\Country $country
     *
     * @return School
     */
    public function setCountry(\App\Entity\Country $country): School
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Getter for region
     *
     * @return \App\Entity\Region|null
     */
    public function getRegion(): ?\App\Entity\Region
    {
        return $this->region;
    }

    /**
     * Fluent setter for region
     *
     * @param \App\Entity\Region|null $region
     *
     * @return School
     */
    public function setRegion(?\App\Entity\Region $region): School
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Getter for street
     *
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Fluent setter for street
     *
     * @param string $street
     *
     * @return School
     */
    public function setStreet(string $street): School
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Getter for city
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Fluent setter for city
     *
     * @param string $city
     *
     * @return School
     */
    public function setCity(string $city): School
    {
        $this->city = $city;

        return $this;
    }
}
<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Country (for ex. Czech republic)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="countries", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="uq_countries_code", columns={"code"}),
 *      @ORM\UniqueConstraint(name="uq_countries_name", columns={"name"})
 * })
 * @ORM\Entity
 */
class Country
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="country_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var string Official name
     * @ORM\Column(name="name", type="string", length=70, nullable=false, options={  })
     */
    private string $name;
    /**
     * @var string Official code (for ex. cz for Czech republic)
     * @ORM\Column(name="code", type="string", length=2, nullable=false, options={  })
     */
    private string $code;

    /**
     * @ORM\OneToMany(targetEntity="Region", mappedBy="country")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\Region>
     */
    private $regions;
    /**
     * @ORM\OneToMany(targetEntity="School", mappedBy="country")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\School>
     */
    private $schools;

    public function __construct()
    {
        $this->regions = new ArrayCollection;
        $this->schools = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Country
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Country
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): Country
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\Region>|\App\Entity\Region[]
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\Region>|\App\Entity\Region[] $regions
     *
     * @return \App\Entity\Country
     */
    public function setRegions(iterable $regions): Country
    {
        if (is_array($regions)) {
            $regions = new ArrayCollection($regions);
        }

        $this->regions = $regions;

        return $this;
    }

    public function addRegion(Region $region): Country
    {
        $this->regions->add($region);

        return $this;
    }

    public function removeRegion(Region $region): Country
    {
        $this->regions->removeElement($region);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\School>|\App\Entity\School[]
     */
    public function getSchools(): Collection
    {
        return $this->schools;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\School>|\App\Entity\School[] $schools
     *
     * @return \App\Entity\Country
     */
    public function setSchools(iterable $schools): Country
    {
        if (is_array($schools)) {
            $schools = new ArrayCollection($schools);
        }

        $this->schools = $schools;

        return $this;
    }

    public function addSchool(School $school): Country
    {
        $this->schools->add($school);

        return $this;
    }

    public function removeSchool(School $school): Country
    {
        $this->schools->removeElement($school);

        return $this;
    }
}
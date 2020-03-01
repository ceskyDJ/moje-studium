<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Region (in US meanings state)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="regions", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="uq_regions_name_country", columns={"name", "country_id"})
 * })
 * @ORM\Entity
 */
class Region
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="region_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private ?int $id = null;
    /**
     * @var string Official name (for ex. Pardubický kraj in Czech republic)
     * @ORM\Column(name="name", type="string", length=20, nullable=false, options={  })
     */
    private string $name;
    /**
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="regions")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id", nullable=false)
     * @var \App\Entity\Country Country, where the region is
     */
    private Country $country;

    /**
     * @ORM\OneToMany(targetEntity="School", mappedBy="region")
     *
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\School>
     */
    private Collection $schools;

    public function __construct()
    {
        $this->schools = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Region
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Region
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): Region
    {
        $this->country = $country;

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
     * @return \App\Entity\Region
     */
    public function setSchools(iterable $schools): Region
    {
        if (is_array($schools)) {
            $schools = new ArrayCollection($schools);
        }

        $this->schools = $schools;

        return $this;
    }

    public function addSchool(School $school): Region
    {
        $this->schools->add($school);

        return $this;
    }

    public function removeSchool(School $school): Region
    {
        $this->schools->removeElement($school);

        return $this;
    }
}
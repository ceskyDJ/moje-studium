<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * School
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="schools", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="uq_schools_name", columns={"name"})
 * })
 * @ORM\Entity
 */
class School
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="school_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var string Official name
     * @ORM\Column(name="name", type="string", length=125, nullable=false, options={  })
     */
    private string $name;
    /**
     * @var string Address: street (with house number)
     * @ORM\Column(name="street", type="string", length=50, nullable=true, options={  })
     */
    private string $street;
    /**
     * @var string Address: city
     * @ORM\Column(name="city", type="string", length=35, nullable=true, options={  })
     */
    private string $city;
    /**
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="schools")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id", nullable=false)
     * @var \App\Entity\Country Address: country
     */
    private Country $country;
    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="schools")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="region_id")
     * @var \App\Entity\Region|null Address: region
     */
    private ?Region $region;

    /**
     * @ORM\OneToMany(targetEntity="SchoolClass", mappedBy="school")
     *
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SchoolClass>
     */
    private Collection $classes;

    public function __construct()
    {
        $this->classes = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): School
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): School
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): School
    {
        $this->country = $country;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): School
    {
        $this->region = $region;

        return $this;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): School
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): School
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\SchoolClass>|\App\Entity\SchoolClass[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\SchoolClass>|\App\Entity\SchoolClass[] $classes
     *
     * @return \App\Entity\School
     */
    public function setClasses(iterable $classes): School
    {
        if (is_array($classes)) {
            $classes = new ArrayCollection($classes);
        }

        $this->classes = $classes;

        return $this;
    }

    public function addClass(SchoolClass $class): School
    {
        $this->classes->add($class);

        return $this;
    }

    public function removeClass(SchoolClass $class): School
    {
        $this->classes->removeElement($class);

        return $this;
    }
}
<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Teacher
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="teachers", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_teachers_class_shortcut", columns={"class_id", "shortcut"})
 * })
 * @ORM\Entity
 */
class Teacher
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="teacher_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var string First name
     * @ORM\Column(name="first_name", type="string", length=35, nullable=false, options={  })
     */
    private string $firstName;
    /**
     * @var string Last name
     * @ORM\Column(name="last_name", type="string", length=50, nullable=false, options={  })
     */
    private string $lastName;
    /**
     * @var string Academic degree(s) before name
     * @ORM\Column(name="degree_before", type="string", length=15, nullable=true, options={  })
     */
    private string $degreeBefore;
    /**
     * @var string Academic degree(s) after name
     * @ORM\Column(name="degree_after", type="string", length=10, nullable=true, options={  })
     */
    private string $degreeAfter;
    /**
     * @var string Shortcut (for timetable, a few uppercase letters from last name)
     * @ORM\Column(name="shortcut", type="string", length=3, nullable=false, options={  })
     */
    private string $shortcut;
    /**
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="teachers")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="class_id", nullable=false, onDelete="CASCADE")
     * @var \App\Entity\SchoolClass Class where the teacher teaches
     */
    private SchoolClass $class;

    /**
     * @ORM\OneToMany(targetEntity="TaughtGroup", mappedBy="teacher")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\TaughtGroup>
     */
    private Collection $taughtGroups;

    public function __construct()
    {
        $this->taughtGroups = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Teacher
    {
        $this->id = $id;

        return $this;
    }

    public function getClass(): SchoolClass
    {
        return $this->class;
    }

    public function setClass(SchoolClass $class): Teacher
    {
        $this->class = $class;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Teacher
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Teacher
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDegreeBefore(): string
    {
        return $this->degreeBefore;
    }

    public function setDegreeBefore(string $degreeBefore): Teacher
    {
        $this->degreeBefore = $degreeBefore;

        return $this;
    }

    public function getDegreeAfter(): string
    {
        return $this->degreeAfter;
    }

    public function setDegreeAfter(string $degreeAfter): Teacher
    {
        $this->degreeAfter = $degreeAfter;

        return $this;
    }

    public function getShortcut(): string
    {
        return $this->shortcut;
    }

    public function setShortcut(string $shortcut): Teacher
    {
        $this->shortcut = $shortcut;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\TaughtGroup>|\App\Entity\TaughtGroup[]
     */
    public function getTaughtGroups(): Collection
    {
        return $this->taughtGroups;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\TaughtGroup>|\App\Entity\TaughtGroup[] $taughtGroups
     *
     * @return \App\Entity\Teacher
     */
    public function setTaughtGroups(iterable $taughtGroups): Teacher
    {
        if (is_array($taughtGroups)) {
            $taughtGroups = new ArrayCollection($taughtGroups);
        }

        $this->taughtGroups = $taughtGroups;

        return $this;
    }

    public function addTaughtGroup(TaughtGroup $taughtGroup): Teacher
    {
        $this->taughtGroups->add($taughtGroup);

        return $this;
    }

    public function removeTaughtGroup(TaughtGroup $taughtGroup): Teacher
    {
        $this->taughtGroups->removeElement($taughtGroup);

        return $this;
    }
}
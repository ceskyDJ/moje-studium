<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * School subject
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="subjects", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_subjects_class_name", columns={"class_id", "name"}),
 *     @ORM\UniqueConstraint(name="uq_subjects_class_shortcut", columns={"class_id", "shortcut"})
 * })
 * @ORM\Entity
 */
class SchoolSubject
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="subject_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var string Long name
     * @ORM\Column(name="name", type="string", length=60, nullable=false, options={  })
     */
    private string $name;
    /**
     * @var string Shortcut (a few uppercase letters)
     * @ORM\Column(name="shortcut", type="string", length=5, nullable=false, options={  })
     */
    private string $shortcut;
    /**
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="subjects")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="class_id", nullable=false, onDelete="CASCADE")
     * @var \App\Entity\SchoolClass Class that has the subject
     */
    private SchoolClass $class;

    /**
     * @ORM\OneToMany(targetEntity="TaughtGroup", mappedBy="subject")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\TaughtGroup>
     */
    private Collection $taughtGroups;
    /**
     * @ORM\OneToMany(targetEntity="PrivateReminder", mappedBy="subject")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\PrivateReminder>
     */
    private Collection $userReminders;

    public function __construct()
    {
        $this->taughtGroups = new ArrayCollection;
        $this->userReminders = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): SchoolSubject
    {
        $this->id = $id;

        return $this;
    }

    public function getClass(): SchoolClass
    {
        return $this->class;
    }

    public function setClass(SchoolClass $class): SchoolSubject
    {
        $this->class = $class;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): SchoolSubject
    {
        $this->name = $name;

        return $this;
    }

    public function getShortcut(): string
    {
        return $this->shortcut;
    }

    public function setShortcut(string $shortcut): SchoolSubject
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
     * @return \App\Entity\SchoolSubject
     */
    public function setTaughtGroups(iterable $taughtGroups): SchoolSubject
    {
        if (is_array($taughtGroups)) {
            $taughtGroups = new ArrayCollection($taughtGroups);
        }

        $this->taughtGroups = $taughtGroups;

        return $this;
    }

    public function addTaughtGroup(TaughtGroup $taughtGroup): SchoolSubject
    {
        $this->taughtGroups->add($taughtGroup);

        return $this;
    }

    public function removeTaughtGroup(TaughtGroup $taughtGroup): SchoolSubject
    {
        $this->taughtGroups->removeElement($taughtGroup);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\PrivateReminder>|\App\Entity\PrivateReminder[]
     */
    public function getUserReminders(): Collection
    {
        return $this->userReminders;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\PrivateReminder>|\App\Entity\PrivateReminder[] $userReminders
     *
     * @return \App\Entity\SchoolSubject
     */
    public function setUserReminders(iterable $userReminders): SchoolSubject
    {
        if (is_array($userReminders)) {
            $userReminders = new ArrayCollection($userReminders);
        }

        $this->userReminders = $userReminders;

        return $this;
    }

    public function addUserReminder(PrivateReminder $userReminder): SchoolSubject
    {
        $this->userReminders->add($userReminder);

        return $this;
    }

    public function removeUserReminder(PrivateReminder $userReminder): SchoolSubject
    {
        $this->userReminders->removeElement($userReminder);

        return $this;
    }
}
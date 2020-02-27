<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Group of students in the class
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="groups", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_groups_class_name", columns={"name", "class_id"})
 * })
 * @ORM\Entity
 */
class ClassGroup
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="group_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var string Name (for ex. S1, S2, ZD1)
     * @ORM\Column(name="name", type="string", length=20, nullable=false, options={  })
     */
    private string $name;
    /**
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="groups")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="class_id", nullable=false, onDelete="CASCADE")
     * @var \App\Entity\SchoolClass School class, where the group comes from
     */
    private SchoolClass $class;

    /**
     * @ORM\OneToMany(targetEntity="TaughtGroup", mappedBy="group")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\TaughtGroup>
     */
    private Collection $taughtGroups;
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\User>
     */
    private Collection $users;

    public function __construct()
    {
        $this->taughtGroups = new ArrayCollection;
        $this->users = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): ClassGroup
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ClassGroup
    {
        $this->name = $name;

        return $this;
    }

    public function getClass(): SchoolClass
    {
        return $this->class;
    }

    public function setClass(SchoolClass $class): ClassGroup
    {
        $this->class = $class;

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
     * @return \App\Entity\ClassGroup
     */
    public function setTaughtGroups(iterable $taughtGroups): ClassGroup
    {
        if (is_array($taughtGroups)) {
            $taughtGroups = new ArrayCollection($taughtGroups);
        }

        $this->taughtGroups = $taughtGroups;

        return $this;
    }

    public function addTaughtGroup(TaughtGroup $taughtGroup): ClassGroup
    {
        $this->taughtGroups->add($taughtGroup);

        return $this;
    }

    public function removeTaughtGroup(TaughtGroup $taughtGroup): ClassGroup
    {
        $this->taughtGroups->removeElement($taughtGroup);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\User>|\App\Entity\User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\User>|\App\Entity\User[] $users
     *
     * @return ClassGroup
     */
    public function setUsers(Collection $users): ClassGroup
    {
        $this->users = $users;

        foreach ($users as $user) {
            $user->addGroup($this);
        }

        return $this;
    }

    public function addUser(User $user): ClassGroup
    {
        if ($this->users->contains($user)) {
            return $this;
        }

        $this->users->add($user);
        $user->addGroup($this);

        return $this;
    }

    public function removeUser(User $user): ClassGroup
    {
        if (!$this->users->contains($user)) {
            return $this;
        }

        $this->users->removeElement($user);
        $user->removeGroup($this);

        return $this;
    }
}
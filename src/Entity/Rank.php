<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mammoth\Security\Entity\IRank;
use function is_array;

/**
 * Rank (permission group) for user
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="ranks", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="uq_ranks_name", columns={"name"})
 * })
 * @ORM\Entity
 */
class Rank implements IRank
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="rank_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private ?int $id = null;
    /**
     * @var string Short name (for printing)
     * @ORM\Column(name="name", type="string", length=20, nullable=false, options={  })
     */
    private string $name;
    /**
     * @var int Permission level (how many permissions contains the rank)
     * @ORM\Column(name="permission_level", type="smallint", length=2, nullable=false, options={ "unsigned": true })
     */
    private int $permissionLevel;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="rank")
     *
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\User>
     */
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Rank
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Rank
    {
        $this->name = $name;

        return $this;
    }

    public function getPermissionLevel(): int
    {
        return $this->permissionLevel;
    }

    public function setPermissionLevel(int $permissionLevel): Rank
    {
        $this->permissionLevel = $permissionLevel;

        return $this;
    }

    public function getType(): int
    {
        return $this->getPermissionLevel();
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
     * @return \App\Entity\Rank
     */
    public function setUsers(iterable $users): Rank
    {
        if (is_array($users)) {
            $users = new ArrayCollection($users);
        }

        $this->users = $users;

        return $this;
    }

    public function addUser(User $user): Rank
    {
        $this->users->add($user);

        return $this;
    }

    public function removeUser(User $user): Rank
    {
        $this->users->removeElement($user);

        return $this;
    }
}
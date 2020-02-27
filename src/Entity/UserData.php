<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User's personal information
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="user_data", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_user_data_email", columns={"email"}),
 *     @ORM\UniqueConstraint(name="uq_user_data_username", columns={"username"})
 * })
 * @ORM\Entity
 */
class UserData
{
    /**
     * @var string Username (login name)
     * @ORM\Column(name="username", type="string", length=25, nullable=false, options={  })
     */
    private string $username;
    /**
     * @var string Email address
     * @ORM\Column(name="email", type="string", length=255, nullable=false, options={  })
     */
    private string $email;
    /**
     * @var string First name
     * @ORM\Column(name="first_name", type="string", length=35, nullable=false, options={  })
     */
    private string $firstName;
    /**
     * @var string Last name (and possible middle names)
     * @ORM\Column(name="last_name", type="string", length=50, nullable=false, options={  })
     */
    private string $lastName;
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="User", inversedBy="data")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE")
     * @var \App\Entity\User Owner of there information
     */
    private User $user;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): UserData
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserData
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): UserData
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): UserData
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): UserData
    {
        $this->user = $user;

        return $this;
    }
}
<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * User of the system
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class User
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var string Username (login name)
     */
    private string $username;
    /**
     * @var string Password (hash form)
     */
    private string $password;
    /**
     * @var \App\Entity\Rank Rank
     */
    private Rank $rank;
    /**
     * @var \App\Entity\SchoolClass|null School class
     */
    private ?SchoolClass $class;

    // Personal data
    /**
     * @var string First name
     */
    private string $firstName;
    /**
     * @var string Last name (includes middle names for simplicity)
     */
    private string $lastName;
    /**
     * @var string Email address
     */
    private string $email;

    // Profile image
    /**
     * @var \App\Entity\ProfileIcon|null Profile image = icon selected from list
     */
    private ?ProfileIcon $profileImage;
    /**
     * @var string|null Profile image's color in HEX code (for ex. #001122)
     */
    private ?string $profileImageColor;

    /**
     * User constructor
     *
     * @param int $id
     * @param string $username
     * @param string $password
     * @param \App\Entity\Rank $rank
     * @param \App\Entity\SchoolClass|null $class
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param \App\Entity\ProfileIcon|null $profileImage
     * @param string|null $profileImageColor
     */
    public function __construct(
        int $id,
        string $username,
        string $password,
        Rank $rank,
        ?SchoolClass $class,
        string $firstName,
        string $lastName,
        string $email,
        ?ProfileIcon $profileImage,
        ?string $profileImageColor
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->rank = $rank;
        $this->class = $class;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->profileImage = $profileImage;
        $this->profileImageColor = $profileImageColor;
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
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Fluent setter for username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Getter for password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Fluent setter for password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getter for rank
     *
     * @return \App\Entity\Rank
     */
    public function getRank(): \App\Entity\Rank
    {
        return $this->rank;
    }

    /**
     * Fluent setter for rank
     *
     * @param \App\Entity\Rank $rank
     *
     * @return User
     */
    public function setRank(\App\Entity\Rank $rank): User
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Getter for class
     *
     * @return \App\Entity\SchoolClass|null
     */
    public function getClass(): ?\App\Entity\SchoolClass
    {
        return $this->class;
    }

    /**
     * Fluent setter for class
     *
     * @param \App\Entity\SchoolClass|null $class
     *
     * @return User
     */
    public function setClass(?\App\Entity\SchoolClass $class): User
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Getter for firstName
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Fluent setter for firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Getter for lastName
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Fluent setter for lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Getter for email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Fluent setter for email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getter for profileImage
     *
     * @return \App\Entity\ProfileIcon|null
     */
    public function getProfileImage(): ?\App\Entity\ProfileIcon
    {
        return $this->profileImage;
    }

    /**
     * Fluent setter for profileImage
     *
     * @param \App\Entity\ProfileIcon|null $profileImage
     *
     * @return User
     */
    public function setProfileImage(?\App\Entity\ProfileIcon $profileImage): User
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * Getter for profileImageColor
     *
     * @return string|null
     */
    public function getProfileImageColor(): ?string
    {
        return $this->profileImageColor;
    }

    /**
     * Fluent setter for profileImageColor
     *
     * @param string|null $profileImageColor
     *
     * @return User
     */
    public function setProfileImageColor(?string $profileImageColor): User
    {
        $this->profileImageColor = $profileImageColor;

        return $this;
    }
}
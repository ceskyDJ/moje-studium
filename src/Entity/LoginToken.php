<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;

/**
 * User's login token for "persistent" login (login for a longer time)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class LoginToken
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\User User that the token has been created for
     */
    private User $user;
    /**
     * @var string Token content
     */
    private string $token;
    /**
     * @var \DateTime When it was created
     */
    private DateTime $created;
    /**
     * @var bool Is it still valid?
     */
    private bool $valid;

    /**
     * LoginToken constructor
     *
     * @param int $id
     * @param \App\Entity\User $user
     * @param string $token
     * @param \DateTime $created
     * @param bool $valid
     */
    public function __construct(int $id, User $user, string $token, DateTime $created, bool $valid)
    {
        $this->id = $id;
        $this->user = $user;
        $this->token = $token;
        $this->created = $created;
        $this->valid = $valid;
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
     * @return LoginToken
     */
    public function setId(int $id): LoginToken
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for user
     *
     * @return \App\Entity\User
     */
    public function getUser(): \App\Entity\User
    {
        return $this->user;
    }

    /**
     * Fluent setter for user
     *
     * @param \App\Entity\User $user
     *
     * @return LoginToken
     */
    public function setUser(\App\Entity\User $user): LoginToken
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Getter for token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Fluent setter for token
     *
     * @param string $token
     *
     * @return LoginToken
     */
    public function setToken(string $token): LoginToken
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Getter for created
     *
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * Fluent setter for created
     *
     * @param \DateTime $created
     *
     * @return LoginToken
     */
    public function setCreated(\DateTime $created): LoginToken
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Getter for valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * Fluent setter for valid
     *
     * @param bool $valid
     *
     * @return LoginToken
     */
    public function setValid(bool $valid): LoginToken
    {
        $this->valid = $valid;

        return $this;
    }
}
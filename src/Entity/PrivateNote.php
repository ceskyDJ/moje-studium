<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Private note (access to owner only)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class PrivateNote
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\User Owner
     */
    private User $owner;
    /**
     * @var string Note
     */
    private string $content;

    /**
     * PrivateNote constructor
     *
     * @param int $id
     * @param \App\Entity\User $owner
     * @param string $content
     */
    public function __construct(int $id, User $owner, string $content)
    {
        $this->id = $id;
        $this->owner = $owner;
        $this->content = $content;
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
     * @return PrivateNote
     */
    public function setId(int $id): PrivateNote
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for owner
     *
     * @return \App\Entity\User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * Fluent setter for owner
     *
     * @param \App\Entity\User $owner
     *
     * @return PrivateNote
     */
    public function setOwner(User $owner): PrivateNote
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Getter for content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Fluent setter for content
     *
     * @param string $content
     *
     * @return PrivateNote
     */
    public function setContent(string $content): PrivateNote
    {
        $this->content = $content;

        return $this;
    }
}
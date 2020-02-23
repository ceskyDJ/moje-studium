<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;

/**
 * Note (for ex. You must bring some books to school)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class Note
{
    /**
     * Owner shared this with his class
     */
    const SHARED_WITH_CLASS = 0;
    /**
     * Owner shared this with logged in user
     */
    const SHARED_WITH_ME = 1;

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
     * @var \DateTime When was it shared?
     */
    private ?DateTime $shared;
    /**
     * @var int How is it shared with the logged in user?
     */
    private ?int $shareType;

    /**
     * Note constructor
     *
     * @param int $id
     * @param \App\Entity\User $owner
     * @param string $content
     * @param \DateTime|null $shared
     * @param int|null $shareType
     */
    public function __construct(int $id, User $owner, string $content, ?DateTime $shared, ?int $shareType)
    {
        $this->id = $id;
        $this->owner = $owner;
        $this->content = $content;
        $this->shared = $shared;
        $this->shareType = $shareType;
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
     * @return Note
     */
    public function setId(int $id): Note
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for owner
     *
     * @return \App\Entity\User
     */
    public function getOwner(): \App\Entity\User
    {
        return $this->owner;
    }

    /**
     * Fluent setter for owner
     *
     * @param \App\Entity\User $owner
     *
     * @return Note
     */
    public function setOwner(\App\Entity\User $owner): Note
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
     * @return Note
     */
    public function setContent(string $content): Note
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for shared
     *
     * @return \DateTime
     */
    public function getShared(): \DateTime
    {
        return $this->shared;
    }

    /**
     * Fluent setter for shared
     *
     * @param \DateTime $shared
     *
     * @return Note
     */
    public function setShared(\DateTime $shared): Note
    {
        $this->shared = $shared;

        return $this;
    }

    /**
     * Getter for shareType
     *
     * @return int
     */
    public function getShareType(): int
    {
        return $this->shareType;
    }

    /**
     * Fluent setter for shareType
     *
     * @param int $shareType
     *
     * @return Note
     */
    public function setShareType(int $shareType): Note
    {
        $this->shareType = $shareType;

        return $this;
    }
}
<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;

/**
 * Shared note (really it's only private one marked as shared)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class SharedNote
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\User|null User that gets access to note
     */
    private ?User $targetUser;
    /**
     * @var \App\Entity\ClassGroup|null Class group that get access to note
     */
    private ?ClassGroup $targetGroup;
    /**
     * @var \App\Entity\PrivateNote Note for sharing
     */
    private PrivateNote $note;
    /**
     * @var \DateTime When it was shared?
     */
    private DateTime $shared;

    /**
     * SharedNote constructor
     *
     * @param int $id
     * @param \App\Entity\User|null $targetUser
     * @param \App\Entity\ClassGroup|null $targetGroup
     * @param \App\Entity\PrivateNote $note
     * @param \DateTime $shared
     */
    public function __construct(
        int $id,
        ?User $targetUser,
        ?ClassGroup $targetGroup,
        PrivateNote $note,
        DateTime $shared
    ) {
        $this->id = $id;
        $this->targetUser = $targetUser;
        $this->targetGroup = $targetGroup;
        $this->note = $note;
        $this->shared = $shared;
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
     * @return SharedNote
     */
    public function setId(int $id): SharedNote
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for targetUser
     *
     * @return \App\Entity\User|null
     */
    public function getTargetUser(): ?User
    {
        return $this->targetUser;
    }

    /**
     * Fluent setter for targetUser
     *
     * @param \App\Entity\User|null $targetUser
     *
     * @return SharedNote
     */
    public function setTargetUser(?User $targetUser): SharedNote
    {
        $this->targetUser = $targetUser;

        return $this;
    }

    /**
     * Getter for targetGroup
     *
     * @return \App\Entity\ClassGroup|null
     */
    public function getTargetGroup(): ?ClassGroup
    {
        return $this->targetGroup;
    }

    /**
     * Fluent setter for targetGroup
     *
     * @param \App\Entity\ClassGroup|null $targetGroup
     *
     * @return SharedNote
     */
    public function setTargetGroup(?ClassGroup $targetGroup): SharedNote
    {
        $this->targetGroup = $targetGroup;

        return $this;
    }

    /**
     * Getter for note
     *
     * @return \App\Entity\PrivateNote
     */
    public function getNote(): PrivateNote
    {
        return $this->note;
    }

    /**
     * Fluent setter for note
     *
     * @param \App\Entity\PrivateNote $note
     *
     * @return SharedNote
     */
    public function setNote(PrivateNote $note): SharedNote
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Getter for shared
     *
     * @return \DateTime
     */
    public function getShared(): DateTime
    {
        return $this->shared;
    }

    /**
     * Fluent setter for shared
     *
     * @param \DateTime $shared
     *
     * @return SharedNote
     */
    public function setShared(DateTime $shared): SharedNote
    {
        $this->shared = $shared;

        return $this;
    }
}
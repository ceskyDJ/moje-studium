<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;

/**
 * Shared reminder (really it's only private one marked as shared)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class SharedReminder
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\User|null User that gets access to the reminder
     */
    private ?User $targetUser;
    /**
     * @var \App\Entity\ClassGroup|null Class group that get access to the reminder
     */
    private ?ClassGroup $targetGroup;
    /**
     * @var \App\Entity\PrivateReminder Reminder for sharing
     */
    private PrivateReminder $reminder;
    /**
     * @var \DateTime When was it shared?
     */
    private DateTime $shared;

    /**
     * SharedReminder constructor
     *
     * @param int $id
     * @param \App\Entity\User|null $targetUser
     * @param \App\Entity\ClassGroup|null $targetGroup
     * @param \App\Entity\PrivateReminder $reminder
     * @param \DateTime $shared
     */
    public function __construct(
        int $id,
        ?User $targetUser,
        ?ClassGroup $targetGroup,
        PrivateReminder $reminder,
        DateTime $shared
    ) {
        $this->id = $id;
        $this->targetUser = $targetUser;
        $this->targetGroup = $targetGroup;
        $this->reminder = $reminder;
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
     * @return SharedReminder
     */
    public function setId(int $id): SharedReminder
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
     * @return SharedReminder
     */
    public function setTargetUser(?User $targetUser): SharedReminder
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
     * @return SharedReminder
     */
    public function setTargetGroup(?ClassGroup $targetGroup): SharedReminder
    {
        $this->targetGroup = $targetGroup;

        return $this;
    }

    /**
     * Getter for reminder
     *
     * @return \App\Entity\PrivateReminder
     */
    public function getReminder(): PrivateReminder
    {
        return $this->reminder;
    }

    /**
     * Fluent setter for reminder
     *
     * @param \App\Entity\PrivateReminder $reminder
     *
     * @return SharedReminder
     */
    public function setReminder(PrivateReminder $reminder): SharedReminder
    {
        $this->reminder = $reminder;

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
     * @return SharedReminder
     */
    public function setShared(DateTime $shared): SharedReminder
    {
        $this->shared = $shared;

        return $this;
    }
}
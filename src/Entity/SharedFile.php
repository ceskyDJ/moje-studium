<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;

/**
 * Shared file or folder (really it's only private one marked as shared)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class SharedFile
{
    /**
     * @var \App\Entity\User|null User that gets the access to file or folder
     */
    private ?User $targetUser;
    /**
     * @var \App\Entity\ClassGroup|null Class group that get the access to file or folder
     */
    private ?ClassGroup $targetGroup;
    /**
     * @var \App\Entity\PrivateFile File for sharing
     */
    private PrivateFile $file;
    /**
     * @var \DateTime When was it shared?
     */
    private DateTime $shared;

    /**
     * SharedFile constructor
     *
     * @param \App\Entity\User|null $targetUser
     * @param \App\Entity\ClassGroup|null $targetGroup
     * @param \App\Entity\PrivateFile $file
     * @param \DateTime $shared
     */
    public function __construct(
        ?User $targetUser,
        ?ClassGroup $targetGroup,
        PrivateFile $file,
        DateTime $shared
    ) {
        $this->targetUser = $targetUser;
        $this->targetGroup = $targetGroup;
        $this->file = $file;
        $this->shared = $shared;
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
     * @return SharedFile
     */
    public function setTargetUser(?User $targetUser): SharedFile
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
     * @return SharedFile
     */
    public function setTargetGroup(?ClassGroup $targetGroup): SharedFile
    {
        $this->targetGroup = $targetGroup;

        return $this;
    }

    /**
     * Getter for file
     *
     * @return \App\Entity\PrivateFile
     */
    public function getFile(): PrivateFile
    {
        return $this->file;
    }

    /**
     * Fluent setter for file
     *
     * @param \App\Entity\PrivateFile $file
     *
     * @return SharedFile
     */
    public function setFile(PrivateFile $file): SharedFile
    {
        $this->file = $file;

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
     * @return SharedFile
     */
    public function setShared(DateTime $shared): SharedFile
    {
        $this->shared = $shared;

        return $this;
    }
}
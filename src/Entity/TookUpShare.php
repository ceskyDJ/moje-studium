<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Took up share (reminder or note) - it's added to user's own live timetable
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class TookUpShare
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\User User owns the shared (but no original) note or reminder
     */
    private User $user;
    /**
     * @var \App\Entity\SharedNote|null Specific shared note that is took up
     */
    private ?SharedNote $sharedNote;
    /**
     * @var \App\Entity\SharedReminder|null Specific shared reminder that is took up
     */
    private ?SharedReminder $sharedReminder;

    /**
     * TookUpShare constructor
     *
     * @param int $id
     * @param \App\Entity\User $user
     * @param \App\Entity\SharedNote|null $sharedNote
     * @param \App\Entity\SharedReminder|null $sharedReminder
     */
    public function __construct(
        int $id,
        User $user,
        ?SharedNote $sharedNote,
        ?SharedReminder $sharedReminder
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->sharedNote = $sharedNote;
        $this->sharedReminder = $sharedReminder;
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
     * @return TookUpShare
     */
    public function setId(int $id): TookUpShare
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for user
     *
     * @return \App\Entity\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Fluent setter for user
     *
     * @param \App\Entity\User $user
     *
     * @return TookUpShare
     */
    public function setUser(User $user): TookUpShare
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Getter for sharedNote
     *
     * @return \App\Entity\SharedNote|null
     */
    public function getSharedNote(): ?SharedNote
    {
        return $this->sharedNote;
    }

    /**
     * Fluent setter for sharedNote
     *
     * @param \App\Entity\SharedNote|null $sharedNote
     *
     * @return TookUpShare
     */
    public function setSharedNote(?SharedNote $sharedNote): TookUpShare
    {
        $this->sharedNote = $sharedNote;

        return $this;
    }

    /**
     * Getter for sharedReminder
     *
     * @return \App\Entity\SharedReminder|null
     */
    public function getSharedReminder(): ?SharedReminder
    {
        return $this->sharedReminder;
    }

    /**
     * Fluent setter for sharedReminder
     *
     * @param \App\Entity\SharedReminder|null $sharedReminder
     *
     * @return TookUpShare
     */
    public function setSharedReminder(?SharedReminder $sharedReminder): TookUpShare
    {
        $this->sharedReminder = $sharedReminder;

        return $this;
    }
}
<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;

/**
 * Private reminder (access only to owner)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class PrivateReminder
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\User Owner (creator)
     */
    private User $owner;
    /**
     * @var string Type (homework, school-event, test)
     */
    private string $type;
    /**
     * @var string What happens?
     */
    private string $content;
    /**
     * @var \DateTime When does it happens?
     */
    private DateTime $when;
    /**
     * @var \App\Entity\SchoolSubject Target subject
     */
    private SchoolSubject $subject;

    /**
     * PrivateReminder constructor
     *
     * @param int $id
     * @param \App\Entity\User $user
     * @param string $type
     * @param string $content
     * @param \DateTime $when
     * @param \App\Entity\SchoolSubject $subject
     */
    public function __construct(
        int $id,
        User $user,
        string $type,
        string $content,
        DateTime $when,
        SchoolSubject $subject
    ) {
        $this->id = $id;
        $this->owner = $user;
        $this->type = $type;
        $this->content = $content;
        $this->when = $when;
        $this->subject = $subject;
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
     * @return PrivateReminder
     */
    public function setId(int $id): PrivateReminder
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
     * @return PrivateReminder
     */
    public function setOwner(User $owner): PrivateReminder
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Getter for type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Fluent setter for type
     *
     * @param string $type
     *
     * @return PrivateReminder
     */
    public function setType(string $type): PrivateReminder
    {
        $this->type = $type;

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
     * @return PrivateReminder
     */
    public function setContent(string $content): PrivateReminder
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for when
     *
     * @return \DateTime
     */
    public function getWhen(): DateTime
    {
        return $this->when;
    }

    /**
     * Fluent setter for when
     *
     * @param \DateTime $when
     *
     * @return PrivateReminder
     */
    public function setWhen(DateTime $when): PrivateReminder
    {
        $this->when = $when;

        return $this;
    }

    /**
     * Getter for subject
     *
     * @return \App\Entity\SchoolSubject
     */
    public function getSubject(): SchoolSubject
    {
        return $this->subject;
    }

    /**
     * Fluent setter for subject
     *
     * @param \App\Entity\SchoolSubject $subject
     *
     * @return PrivateReminder
     */
    public function setSubject(SchoolSubject $subject): PrivateReminder
    {
        $this->subject = $subject;

        return $this;
    }
}
<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;

/**
 * Reminder (for ex. test, homework, school event)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class Reminder
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
     * @var \DateTime When was it shared?
     */
    private ?DateTime $shared;
    /**
     * @var int How is it shared with the logged in user?
     */
    private ?int $shareType;

    /**
     * Reminder constructor
     *
     * @param int $id
     * @param \App\Entity\User $owner
     * @param string $type
     * @param string $content
     * @param \DateTime $when
     * @param \App\Entity\SchoolSubject $subject
     * @param \DateTime|null $shared
     * @param int|null $shareType
     */
    public function __construct(
        int $id,
        User $owner,
        string $type,
        string $content,
        DateTime $when,
        SchoolSubject $subject,
        ?DateTime $shared,
        ?int $shareType
    ) {
        $this->id = $id;
        $this->owner = $owner;
        $this->type = $type;
        $this->content = $content;
        $this->when = $when;
        $this->subject = $subject;
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
     * @return Reminder
     */
    public function setId(int $id): Reminder
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
     * @return Reminder
     */
    public function setOwner(User $owner): Reminder
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
     * @return Reminder
     */
    public function setType(string $type): Reminder
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
     * @return Reminder
     */
    public function setContent(string $content): Reminder
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
     * @return Reminder
     */
    public function setWhen(DateTime $when): Reminder
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
     * @return Reminder
     */
    public function setSubject(SchoolSubject $subject): Reminder
    {
        $this->subject = $subject;

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
     * @return Reminder
     */
    public function setShared(DateTime $shared): Reminder
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
     * @return Reminder
     */
    public function setShareType(int $shareType): Reminder
    {
        $this->shareType = $shareType;

        return $this;
    }
}
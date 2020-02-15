<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Notification for user
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class Notification
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\User User that the notification is for
     */
    private User $user;
    /**
     * @var \App\Entity\NotificationText Text (possible with variables to replace for)
     */
    private NotificationText $text;
    /**
     * @var string Final text with replaced variables
     */
    private string $finalText;

    /**
     * Notification constructor
     *
     * @param int $id
     * @param \App\Entity\User $user
     * @param \App\Entity\NotificationText $text
     * @param string $finalText
     */
    public function __construct(int $id, User $user, NotificationText $text, string $finalText)
    {
        $this->id = $id;
        $this->user = $user;
        $this->text = $text;
        $this->finalText = $finalText;
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
     * @return Notification
     */
    public function setId(int $id): Notification
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
     * @return Notification
     */
    public function setUser(User $user): Notification
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Getter for text
     *
     * @return \App\Entity\NotificationText
     */
    public function getText(): NotificationText
    {
        return $this->text;
    }

    /**
     * Fluent setter for text
     *
     * @param \App\Entity\NotificationText $text
     *
     * @return Notification
     */
    public function setText(NotificationText $text): Notification
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Getter for finalText
     *
     * @return string
     */
    public function getFinalText(): string
    {
        return $this->finalText;
    }

    /**
     * Fluent setter for finalText
     *
     * @param string $finalText
     *
     * @return Notification
     */
    public function setFinalText(string $finalText): Notification
    {
        $this->finalText = $finalText;

        return $this;
    }
}
<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Default texts for notifications
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class NotificationText
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var string Notification text (with some variables in it, of course)
     */
    private string $text;

    /**
     * NotificationText constructor
     *
     * @param int $id
     * @param string $text
     */
    public function __construct(int $id, string $text)
    {
        $this->id = $id;
        $this->text = $text;
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
     * @return NotificationText
     */
    public function setId(int $id): NotificationText
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Fluent setter for text
     *
     * @param string $text
     *
     * @return NotificationText
     */
    public function setText(string $text): NotificationText
    {
        $this->text = $text;

        return $this;
    }
}
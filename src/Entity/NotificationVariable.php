<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification variable - it's used for adding some variability to default notification texts
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="notification_variables")
 * @ORM\Entity
 */
class NotificationVariable
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="notification_variable_id", type="integer", length=10, nullable=false, options={ "unsigned":
     *     true })
     */
    private int $id;
    /**
     * @var string Variable name
     * @ORM\Column(name="variable", type="string", length=15, nullable=false, options={  })
     */
    private string $name;
    /**
     * @var string Variable content
     * @ORM\Column(name="content", type="string", length=100, nullable=false, options={  })
     */
    private string $content;
    /**
     * @ORM\ManyToOne(targetEntity="Notification", inversedBy="variables")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="notification_id", nullable=false, onDelete="CASCADE")
     * @var \App\Entity\Notification In which notification
     */
    private Notification $notification;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): NotificationVariable
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): NotificationVariable
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): NotificationVariable
    {
        $this->content = $content;

        return $this;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function setNotification(Notification $notification): NotificationVariable
    {
        $this->notification = $notification;

        return $this;
    }
}
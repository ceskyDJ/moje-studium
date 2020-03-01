<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Default texts for notifications
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="notification_texts", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_notification_texts_text", columns={"text"})
 * })
 * @ORM\Entity
 */
class NotificationText
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="notification_text_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private ?int $id = null;
    /**
     * @var string Notification text (with some variables in it, of course)
     * @ORM\Column(name="text", type="string", length=100, nullable=false, options={  })
     */
    private string $text;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="text")
     *
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\Notification>
     */
    private Collection $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): NotificationText
    {
        $this->id = $id;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): NotificationText
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\Notification>|\App\Entity\Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\Notification>|\App\Entity\Notification[] $notifications
     *
     * @return \App\Entity\NotificationText
     */
    public function setNotifications(iterable $notifications): NotificationText
    {
        if (is_array($notifications)) {
            $notifications = new ArrayCollection($notifications);
        }

        $this->notifications = $notifications;

        return $this;
    }

    public function addNotification(Notification $notification): NotificationText
    {
        $this->notifications->add($notification);

        return $this;
    }

    public function removeNotification(Notification $notification): NotificationText
    {
        $this->notifications->removeElement($notification);

        return $this;
    }
}
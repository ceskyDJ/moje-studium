<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;
use function str_replace;

/**
 * Notification for user
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="notifications")
 * @ORM\Entity
 */
class Notification
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="notification_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notifications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE")
     * @var \App\Entity\User User that the notification is for
     */
    private User $user;
    /**
     * @ORM\ManyToOne(targetEntity="NotificationText", inversedBy="notifications")
     * @ORM\JoinColumn(name="notification_text_id", referencedColumnName="notification_text_id")
     * @var \App\Entity\NotificationText Text (possible with variables to replace for)
     */
    private NotificationText $text;

    /**
     * @ORM\OneToMany(targetEntity="NotificationVariable", mappedBy="notification")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\NotificationVariable>
     */
    private Collection $variables;

    public function __construct()
    {
        $this->variables = new ArrayCollection;
    }

    /**
     * Returns final text
     *
     * @return string Text with replaces variables with their values
     */
    public function getFinalText(): string
    {
        $text = $this->text->getText();

        /**
         * @var $variable \App\Entity\NotificationVariable
         */
        foreach ($this->getVariables() as $variable) {
            $text = str_replace("%{$variable->getName()}%", $variable->getContent(), $text);
        }

        return $text;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Notification
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Notification
    {
        $this->user = $user;

        return $this;
    }

    public function getText(): NotificationText
    {
        return $this->text;
    }

    public function setText(NotificationText $text): Notification
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\NotificationVariable>|\App\Entity\NotificationVariable[]
     */
    public function getVariables(): Collection
    {
        return $this->variables;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\NotificationVariable>|\App\Entity\NotificationVariable[] $variables
     *
     * @return \App\Entity\Notification
     */
    public function setVariables(iterable $variables): Notification
    {
        if (is_array($variables)) {
            $variables = new ArrayCollection($variables);
        }

        $this->variables = $variables;

        return $this;
    }

    public function addNotificationVariable(NotificationVariable $notificationVariable): Notification
    {
        $this->variables->add($notificationVariable);

        return $this;
    }

    public function removeNotificationVariable(NotificationVariable $notificationVariable): Notification
    {
        $this->variables->removeElement($notificationVariable);

        return $this;
    }
}
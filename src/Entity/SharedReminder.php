<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Shared reminder (really it's only private one marked as shared)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="shared_reminders", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_shared_reminders_user_class_reminder", columns={"user_id", "class_id", "user_reminder_id"})
 * })
 * @ORM\Entity
 */
class SharedReminder
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="shared_reminder_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var \DateTime When was it shared?
     * @ORM\Column(name="shared", type="datetime", nullable=false, options={  })
     */
    private DateTime $shared;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sharedReminders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE")
     * @var \App\Entity\User|null User that gets access to the reminder
     */
    private ?User $targetUser;
    /**
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="sharedReminders")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="class_id", onDelete="CASCADE")
     * @var \App\Entity\SchoolClass|null Class that get access to the reminder
     */
    private ?SchoolClass $targetClass;
    /**
     * @ORM\ManyToOne(targetEntity="PrivateReminder", inversedBy="sharedReminders")
     * @ORM\JoinColumn(name="user_reminder_id", referencedColumnName="user_reminder_id")
     * @var \App\Entity\PrivateReminder Reminder for sharing
     */
    private PrivateReminder $reminder;

    /**
     * @ORM\OneToMany(targetEntity="TookUpShare", mappedBy="sharedReminder")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\TookUpShare>
     */
    private Collection $tookUpShares;

    public function __construct()
    {
        $this->tookUpShares = new ArrayCollection;
        $this->shared = new DateTime;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): SharedReminder
    {
        $this->id = $id;

        return $this;
    }

    public function getTargetUser(): ?User
    {
        return $this->targetUser;
    }

    public function setTargetUser(?User $targetUser): SharedReminder
    {
        $this->targetUser = $targetUser;

        return $this;
    }

    public function getTargetClass(): ?SchoolClass
    {
        return $this->targetClass;
    }

    public function setTargetClass(?SchoolClass $targetClass): SharedReminder
    {
        $this->targetClass = $targetClass;

        return $this;
    }

    public function getReminder(): PrivateReminder
    {
        return $this->reminder;
    }

    public function setReminder(PrivateReminder $reminder): SharedReminder
    {
        $this->reminder = $reminder;

        return $this;
    }

    public function getShared(): DateTime
    {
        return $this->shared;
    }

    public function setShared(DateTime $shared): SharedReminder
    {
        $this->shared = $shared;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\TookUpShare>|\App\Entity\TookUpShare[]
     */
    public function getTookUpShares(): Collection
    {
        return $this->tookUpShares;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\TookUpShare>|\App\Entity\TookUpShare[] $tookUpShares
     *
     * @return \App\Entity\SharedReminder
     */
    public function setTookUpShares(iterable $tookUpShares): SharedReminder
    {
        if (is_array($tookUpShares)) {
            $tookUpShares = new ArrayCollection($tookUpShares);
        }

        $this->tookUpShares = $tookUpShares;

        return $this;
    }

    public function addTookUpShare(TookUpShare $tookUpShare): SharedReminder
    {
        $this->tookUpShares->add($tookUpShare);

        return $this;
    }

    public function removeTookUpShare(TookUpShare $tookUpShare): SharedReminder
    {
        $this->tookUpShares->removeElement($tookUpShare);

        return $this;
    }
}
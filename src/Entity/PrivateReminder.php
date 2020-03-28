<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Private reminder (access only to owner)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="user_reminders", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_user_reminders_user_content_date_subject", columns={"user_id", "content", "when", "subject_id"})
 * })
 * @ORM\Entity
 */
class PrivateReminder
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="user_reminder_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private ?int $id = null;
    /**
     * @var string Type (homework, school-event, test)
     * @ORM\Column(name="type", type="string", columnDefinition="ENUM('test', 'homework', 'school-event') NOT NULL", options={  })
     */
    private string $type;
    /**
     * @var string What happens?
     * @ORM\Column(name="content", type="string", length=100, nullable=false, options={  })
     */
    private string $content;
    /**
     * @var \DateTime When does it happens?
     * @ORM\Column(name="`when`", type="date", nullable=false, options={  })
     */
    private DateTime $when;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="privateReminders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false, onDelete="CASCADE")
     * @var \App\Entity\User Owner (creator)
     */
    private User $owner;
    /**
     * @ORM\ManyToOne(targetEntity="SchoolSubject", inversedBy="userReminders")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="subject_id", nullable=false)
     * @var \App\Entity\SchoolSubject Target subject
     */
    private SchoolSubject $subject;

    /**
     * @ORM\OneToMany(targetEntity="SharedReminder", mappedBy="reminder")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SharedReminder>
     */
    private Collection $sharedReminders;

    public function __construct()
    {
        $this->sharedReminders = new ArrayCollection;
    }

    /**
     * Returns nice formatted date for when property
     *
     * @return string Formatted when property for templates
     */
    public function getFormattedWhen(): string
    {
        $daysInWeek = ["po", "út", "st", "čt", "pá", "so", "ne"];
        $when = $this->getWhen();

        return $daysInWeek[$when->format("N") - 1] . " {$when->format("j. n.")}";
    }


    /**
     * Verify whether the file is shared with someone or class
     *
     * @return bool Is this file shared?
     */
    public function isShared(): bool
    {
        return !$this->getSharedReminders()->isEmpty();
    }

    /**
     * Returns who is the file shared with
     *
     * @return string Target group of sharing this file ("class" || "schoolmate")
     */
    public function whoIsSharedWith(): string
    {
        if (!$this->isShared()) {
            return "";
        }

        $shares = $this->getSharedReminders();
        foreach ($shares as $share) {
            if ($share->getTargetClass() !== null) {
                return "class";
            }
        }

        return "schoolmate";
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): PrivateReminder
    {
        $this->id = $id;

        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): PrivateReminder
    {
        $this->owner = $owner;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): PrivateReminder
    {
        $this->type = $type;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): PrivateReminder
    {
        $this->content = $content;

        return $this;
    }

    public function getWhen(): DateTime
    {
        return $this->when;
    }

    public function setWhen(DateTime $when): PrivateReminder
    {
        $this->when = $when;

        return $this;
    }

    public function getSubject(): SchoolSubject
    {
        return $this->subject;
    }

    public function setSubject(SchoolSubject $subject): PrivateReminder
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\SharedReminder>|\App\Entity\SharedReminder[]
     */
    public function getSharedReminders(): Collection
    {
        return $this->sharedReminders;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\SharedReminder>|\App\Entity\SharedReminder[] $sharedReminders
     *
     * @return \App\Entity\PrivateReminder
     */
    public function setSharedReminders(iterable $sharedReminders): PrivateReminder
    {
        if (is_array($sharedReminders)) {
            $sharedReminders = new ArrayCollection($sharedReminders);
        }

        $this->sharedReminders = $sharedReminders;

        return $this;
    }

    public function addSharedReminder(SharedReminder $sharedReminder): PrivateReminder
    {
        $this->sharedReminders->add($sharedReminder);

        return $this;
    }

    public function removeSharedReminder(SharedReminder $sharedReminder): PrivateReminder
    {
        $this->sharedReminders->removeElement($sharedReminder);

        return $this;
    }
}
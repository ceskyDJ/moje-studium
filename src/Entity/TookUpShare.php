<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Took up share (reminder or note) - it's added to user's own live timetable
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="took_up_shares")
 * @ORM\Entity
 */
class TookUpShare
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @ORM\Column(name="took_up_share_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tookUpShares")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false, onDelete="CASCADE")
     * @var \App\Entity\User User owns the shared (but no original) note or reminder
     */
    private User $user;
    /**
     * @ORM\ManyToOne(targetEntity="SharedNote", inversedBy="tookUpShares")
     * @ORM\JoinColumn(name="shared_note_id", referencedColumnName="shared_note_id")
     * @var \App\Entity\SharedNote|null Specific shared note that is took up
     */
    private ?SharedNote $sharedNote;
    /**
     * @ORM\ManyToOne(targetEntity="SharedReminder", inversedBy="tookUpShares")
     * @ORM\JoinColumn(name="shared_reminder_id", referencedColumnName="shared_reminder_id")
     * @var \App\Entity\SharedReminder|null Specific shared reminder that is took up
     */
    private ?SharedReminder $sharedReminder;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): TookUpShare
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): TookUpShare
    {
        $this->user = $user;

        return $this;
    }

    public function getSharedNote(): ?SharedNote
    {
        return $this->sharedNote;
    }

    public function setSharedNote(?SharedNote $sharedNote): TookUpShare
    {
        $this->sharedNote = $sharedNote;

        return $this;
    }

    public function getSharedReminder(): ?SharedReminder
    {
        return $this->sharedReminder;
    }

    public function setSharedReminder(?SharedReminder $sharedReminder): TookUpShare
    {
        $this->sharedReminder = $sharedReminder;

        return $this;
    }
}
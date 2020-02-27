<?php

declare(strict_types = 1);

namespace App\Entity;

use Cassandra\Date;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Shared note (really it's only private one marked as shared)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="shared_notes", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_shared_notes_user_class_note", columns={"user_id", "class_id", "user_note_id"})
 * })
 * @ORM\Entity
 */
class SharedNote
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="shared_note_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var \DateTime When it was shared?
     * @ORM\Column(name="shared", type="datetime", nullable=false, options={  })
     */
    private DateTime $shared;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sharedNotes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE")
     * @var \App\Entity\User|null User that gets access to note
     */
    private ?User $targetUser;
    /**
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="sharedNotes")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="class_id", onDelete="CASCADE")
     * @var \App\Entity\SchoolClass|null Class that get access to note
     */
    private ?SchoolClass $targetClass;
    /**
     * @ORM\ManyToOne(targetEntity="PrivateNote", inversedBy="sharedNotes")
     * @ORM\JoinColumn(name="user_note_id", referencedColumnName="user_note_id", nullable=false)
     * @var \App\Entity\PrivateNote Note for sharing
     */
    private PrivateNote $note;

    /**
     * @ORM\OneToMany(targetEntity="TookUpShare", mappedBy="sharedNote")
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

    public function setId(int $id): SharedNote
    {
        $this->id = $id;

        return $this;
    }

    public function getTargetUser(): ?User
    {
        return $this->targetUser;
    }

    public function setTargetUser(?User $targetUser): SharedNote
    {
        $this->targetUser = $targetUser;

        return $this;
    }

    public function getTargetClass(): ?SchoolClass
    {
        return $this->targetClass;
    }

    public function setTargetClass(?SchoolClass $targetClass): SharedNote
    {
        $this->targetClass = $targetClass;

        return $this;
    }

    public function getNote(): PrivateNote
    {
        return $this->note;
    }

    public function setNote(PrivateNote $note): SharedNote
    {
        $this->note = $note;

        return $this;
    }

    public function getShared(): DateTime
    {
        return $this->shared;
    }

    public function setShared(DateTime $shared): SharedNote
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
     * @return \App\Entity\SharedNote
     */
    public function setTookUpShares(iterable $tookUpShares): SharedNote
    {
        if (is_array($tookUpShares)) {
            $tookUpShares = new ArrayCollection($tookUpShares);
        }

        $this->tookUpShares = $tookUpShares;

        return $this;
    }

    public function addTookUpShare(TookUpShare $tookUpShare): SharedNote
    {
        $this->tookUpShares->add($tookUpShare);

        return $this;
    }

    public function removeTookUpShare(TookUpShare $tookUpShare): SharedNote
    {
        $this->tookUpShares->removeElement($tookUpShare);

        return $this;
    }
}
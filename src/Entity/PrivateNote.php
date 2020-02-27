<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Private note (access to owner only)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="user_notes")
 * @ORM\Entity
 */
class PrivateNote
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="user_note_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var string Note
     * @ORM\Column(name="content", type="string", length=200, nullable=false, options={  })
     */
    private string $content;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="privateNotes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE")
     * @var \App\Entity\User Owner
     */
    private User $owner;

    /**
     * @ORM\OneToMany(targetEntity="SharedNote", mappedBy="note")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SharedNote>
     */
    private Collection $sharedNotes;

    public function __construct()
    {
        $this->sharedNotes = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): PrivateNote
    {
        $this->id = $id;

        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): PrivateNote
    {
        $this->owner = $owner;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): PrivateNote
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\SharedNote>|\App\Entity\SharedNote[]
     */
    public function getSharedNotes(): Collection
    {
        return $this->sharedNotes;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\SharedNote>|\App\Entity\SharedNote[] $sharedNotes
     *
     * @return \App\Entity\PrivateNote
     */
    public function setSharedNotes(iterable $sharedNotes): PrivateNote
    {
        if (is_array($sharedNotes)) {
            $sharedNotes = new ArrayCollection($sharedNotes);
        }

        $this->sharedNotes = $sharedNotes;

        return $this;
    }

    public function addSharedNote(SharedNote $sharedNote): PrivateNote
    {
        $this->sharedNotes->add($sharedNote);

        return $this;
    }

    public function removeSharedNote(SharedNote $sharedNote): PrivateNote
    {
        $this->sharedNotes->removeElement($sharedNote);

        return $this;
    }
}
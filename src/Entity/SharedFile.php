<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Shared file or folder (really it's only private one marked as shared)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="shared_files", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_shared_files_user_class_file", columns={"user_id", "class_id", "user_file_id"})
 * })
 * @ORM\Entity
 */
class SharedFile
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @ORM\Column(name="shared_file_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var \DateTime When was it shared?
     * @ORM\Column(name="shared", type="datetime", nullable=false, options={  })
     */
    private DateTime $shared;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sharedFiles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE")
     * @var \App\Entity\User|null User that gets the access to file or folder
     */
    private ?User $targetUser;
    /**
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="sharedFiles")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="class_id", onDelete="CASCADE")
     * @var \App\Entity\SchoolClass|null Class that get the access to file or folder
     */
    private ?SchoolClass $targetClass;
    /**
     * @ORM\ManyToOne(targetEntity="PrivateFile", inversedBy="sharedFiles")
     * @ORM\JoinColumn(name="user_file_id", referencedColumnName="user_file_id")
     * @var \App\Entity\PrivateFile File for sharing
     */
    private PrivateFile $file;

    public function __construct()
    {
        $this->shared = new DateTime;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): SharedFile
    {
        $this->id = $id;

        return $this;
    }

    public function getTargetUser(): ?User
    {
        return $this->targetUser;
    }

    public function setTargetUser(?User $targetUser): SharedFile
    {
        $this->targetUser = $targetUser;

        return $this;
    }

    public function getTargetClass(): ?SchoolClass
    {
        return $this->targetClass;
    }

    public function setTargetClass(?SchoolClass $targetClass): SharedFile
    {
        $this->targetClass = $targetClass;

        return $this;
    }

    public function getFile(): PrivateFile
    {
        return $this->file;
    }

    public function setFile(PrivateFile $file): SharedFile
    {
        $this->file = $file;

        return $this;
    }

    public function getShared(): DateTime
    {
        return $this->shared;
    }

    public function setShared(DateTime $shared): SharedFile
    {
        $this->shared = $shared;

        return $this;
    }
}
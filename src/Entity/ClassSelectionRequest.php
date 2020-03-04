<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User's request for class selection
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="class_requests")
 * @ORM\Entity
 */
class ClassSelectionRequest
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="class_request_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private ?int $id = null;
    /**
     * @var bool|null Is it approved? If null, the request is still open
     * @ORM\Column(name="approved", type="boolean", length=1, nullable=true, options={  })
     */
    private ?bool $approved = null;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="selectionRequests")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false, onDelete="CASCADE")
     * @var \App\Entity\User Applicant (request creator)
     */
    private User $user;
    /**
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="selectionRequests")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="class_id", nullable=false, onDelete="CASCADE")
     * @var \App\Entity\SchoolClass Class which applicant wants
     */
    private SchoolClass $class;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): ClassSelectionRequest
    {
        $this->id = $id;

        return $this;
    }

    public function getApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(?bool $approved): ClassSelectionRequest
    {
        $this->approved = $approved;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): ClassSelectionRequest
    {
        $this->user = $user;

        return $this;
    }

    public function getClass(): SchoolClass
    {
        return $this->class;
    }

    public function setClass(SchoolClass $class): ClassSelectionRequest
    {
        $this->class = $class;

        return $this;
    }
}
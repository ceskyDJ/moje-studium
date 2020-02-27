<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * User's login token for "persistent" login (login for a longer time)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="tokens", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_tokens_token", columns={"token"})
 * })
 * @ORM\Entity
 */
class LoginToken
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @ORM\Column(name="token_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var string Token content
     * @ORM\Column(name="token", type="string", length=512, nullable=false, options={  })
     */
    private string $content;
    /**
     * @var \DateTime When it was created
     * @ORM\Column(name="created", type="datetime", nullable=false, options={  })
     */
    private DateTime $created;
    /**
     * @var bool Is it still valid?
     * @ORM\Column(name="valid", type="boolean", length=1, nullable=false, options={ "default": 1 })
     */
    private bool $valid;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tokens")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE")
     * @var \App\Entity\User User that the token has been created for
     */
    private User $user;

    public function __construct()
    {
        $this->created = new DateTime;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): LoginToken
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): LoginToken
    {
        $this->user = $user;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): LoginToken
    {
        $this->content = $content;

        return $this;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function setCreated(DateTime $created): LoginToken
    {
        $this->created = $created;

        return $this;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): LoginToken
    {
        $this->valid = $valid;

        return $this;
    }
}
<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile icons selected by user
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="profile_images")
 * @ORM\Entity
 */
class ProfileImage
{
    /**
     * @var string User defined color in HEX form (for ex. #A1B2C3)
     * @ORM\Column(name="color", type="string", length=7, nullable=false, options={  })
     */
    private string $color;
    /**
     * @ORM\ManyToOne(targetEntity="ProfileIcon", inversedBy="profileImages")
     * @ORM\JoinColumn(name="profile_icon_id", referencedColumnName="profile_icon_id")
     * @var \App\Entity\ProfileIcon Chosen icon
     */
    private ProfileIcon $icon;
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="User", inversedBy="profileImage")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE")
     * @var \App\Entity\User
     */
    private User $user;

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): ProfileImage
    {
        $this->color = $color;

        return $this;
    }

    public function getIcon(): ProfileIcon
    {
        return $this->icon;
    }

    public function setIcon(ProfileIcon $icon): ProfileImage
    {
        $this->icon = $icon;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): ProfileImage
    {
        $this->user = $user;

        return $this;
    }
}
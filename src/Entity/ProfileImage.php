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
     * @var string User defined background color in HEX form (for ex. #A1B2C3)
     * @ORM\Column(name="background_color", type="string", length=7, nullable=false, options={ "fixed": true })
     */
    private string $backgroundColor;
    /**
     * @var string User defined icon color in HEX form (for ex. #A1B2C3)
     * @ORM\Column(name="icon_color", type="string", length=7, nullable=false, options={ "fixed": true })
     */
    private string $iconColor;
    /**
     * @ORM\ManyToOne(targetEntity="ProfileIcon", inversedBy="profileImages")
     * @ORM\JoinColumn(name="profile_icon_id", referencedColumnName="profile_icon_id", nullable=false)
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

    public function __construct()
    {
        $this->backgroundColor = "#84ADAA";
        $this->iconColor = "#F3D452";
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): ProfileImage
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getIconColor(): string
    {
        return $this->iconColor;
    }

    public function setIconColor(string $iconColor): ProfileImage
    {
        $this->iconColor = $iconColor;

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
<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Profile icon available to use by user as profile image
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="profile_icons", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_profile_icons_name", columns={"name"})
 * })
 * @ORM\Entity
 */
class ProfileIcon
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="profile_icon_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private ?int $id = null;
    /**
     * @var string Name of the class to use (for ex. duck-profile-i) in profile image
     * @ORM\Column(name="name", type="string", length=20, nullable=false, options={  })
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity="ProfileImage", mappedBy="icon")
     *
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\ProfileImage>
     */
    private Collection $profileImages;

    public function __construct()
    {
        $this->profileImages = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): ProfileIcon
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ProfileIcon
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\ProfileImage>|\App\Entity\ProfileImage[]
     */
    public function getProfileImages(): Collection
    {
        return $this->profileImages;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\ProfileImage>|\App\Entity\ProfileImage[] $profileImages
     *
     * @return \App\Entity\ProfileIcon
     */
    public function setProfileImages(iterable $profileImages): ProfileIcon
    {
        if (is_array($profileImages)) {
            $profileImages = new ArrayCollection($profileImages);
        }

        $this->profileImages = $profileImages;

        return $this;
    }

    public function addProfileImage(ProfileImage $profileImage): ProfileIcon
    {
        $this->profileImages->add($profileImage);

        return $this;
    }

    public function removeProfileImage(ProfileImage $profileImage): ProfileIcon
    {
        $this->profileImages->removeElement($profileImage);

        return $this;
    }
}
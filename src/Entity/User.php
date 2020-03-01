<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mammoth\Security\Entity\IRank;
use Mammoth\Security\Entity\IUser;
use Mammoth\Security\Entity\UserData;
use function is_array;

/**
 * User of the system
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User implements IUser
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="user_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private ?int $id = null;
    /**
     * @var string Password (hash form)
     * @ORM\Column(name="password", type="string", length=255, nullable=false, options={  })
     */
    private string $password;
    /**
     * @var bool Has this user confirmed address?
     * @ORM\Column(name="confirmed", type="boolean", length=1, nullable=false, options={ "default": 0 })
     */
    private bool $confirmed;
    /**
     * @var bool Is this user's first login to the system? (after registration)
     * @ORM\Column(name="first_login", type="boolean", length=1, nullable=false, options={ "default": 1 })
     */
    private bool $firstLogin;
    /**
     * @ORM\ManyToOne(targetEntity="Rank", inversedBy="users")
     * @ORM\JoinColumn(name="rank_id", referencedColumnName="rank_id", nullable=false)
     * @var \App\Entity\Rank Rank
     */
    private Rank $rank;
    /**
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="users")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="class_id", onDelete="SET NULL")
     * @var \App\Entity\SchoolClass|null School class
     */
    private ?SchoolClass $class;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="user")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\Notification>
     */
    private Collection $notifications;
    /**
     * @ORM\OneToOne(targetEntity="ProfileImage", mappedBy="user")
     * @var \App\Entity\ProfileImage|null
     */
    private ?ProfileImage $profileImage = null;
    /**
     * @ORM\OneToMany(targetEntity="SharedFile", mappedBy="targetUser")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SharedFile>
     */
    private Collection $sharedFiles;
    /**
     * @ORM\OneToMany(targetEntity="SharedNote", mappedBy="targetUser")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SharedNote>
     */
    private Collection $sharedNotes;
    /**
     * @ORM\OneToMany(targetEntity="SharedReminder", mappedBy="targetUser")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SharedReminder>
     */
    private Collection $sharedReminders;
    /**
     * @ORM\OneToMany(targetEntity="LoginToken", mappedBy="user")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\LoginToken>
     */
    private Collection $tokens;
    /**
     * @ORM\OneToMany(targetEntity="TookUpShare", mappedBy="user")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\TookUpShare>
     */
    private Collection $tookUpShares;
    /**
     * @ORM\OneToOne(targetEntity="UserData", mappedBy="user")
     * @var \App\Entity\UserData
     */
    private ?\App\Entity\UserData $data = null;
    /**
     * @ORM\OneToMany(targetEntity="PrivateFile", mappedBy="owner")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\PrivateFile>
     */
    private Collection $privateFiles;
    /**
     * @ORM\OneToMany(targetEntity="PrivateNote", mappedBy="owner")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\PrivateNote>
     */
    private Collection $privateNotes;
    /**
     * @ORM\OneToMany(targetEntity="PrivateReminder", mappedBy="owner")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\PrivateReminder>
     */
    private Collection $privateReminders;
    /**
     * @ORM\ManyToMany(targetEntity="ClassGroup", inversedBy="users")
     * @ORM\JoinTable(
     *  name="users_in_groups",
     *  joinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="group_id", referencedColumnName="group_id", onDelete="CASCADE")
     *  }
     * )
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\ClassGroup>
     */
    private Collection $groups;

    public function __construct()
    {
        $this->confirmed = false;
        $this->firstLogin = true;
        $this->notifications = new ArrayCollection;
        $this->sharedFiles = new ArrayCollection;
        $this->sharedNotes = new ArrayCollection;
        $this->sharedReminders = new ArrayCollection;
        $this->tokens = new ArrayCollection;
        $this->tookUpShares = new ArrayCollection;
        $this->privateFiles = new ArrayCollection;
        $this->privateNotes = new ArrayCollection;
        $this->privateReminders = new ArrayCollection;
    }

    public function getId(): string
    {
        return (string)$this->id;
    }

    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): User
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    public function isFirstLogin(): bool
    {
        return $this->firstLogin;
    }

    public function setFirstLogin(bool $firstLogin): User
    {
        $this->firstLogin = $firstLogin;

        return $this;
    }

    public function getClass(): ?SchoolClass
    {
        return $this->class;
    }

    public function setClass(?SchoolClass $class): User
    {
        $this->class = $class;

        return $this;
    }

    public function getRank(): Rank
    {
        return $this->rank;
    }

    public function setRank(Rank $rank): User
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getProperty(string $name): ?UserData
    {
        return ($this->getProperties()[$name] ?? null);
    }

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        if ($this->class !== null) {
            $properties = [new UserData("class", $this->class->getName())];
        } else {
            $properties = [];
        }

        $properties[] = new UserData("first-name", $this->data->getFirstName());
        $properties[] = new UserData("last-name", $this->data->getLastName());
        $properties[] = new UserData("email", $this->data->getEmail());

        return $properties;
    }

    /**
     * @inheritDoc
     */
    public function isLoggedIn(): bool
    {
        return ($this->getRank()->getPermissionLevel() !== IRank::VISITOR);
    }

    /**
     * @inheritDoc
     */
    public function getUserName(): string
    {
        return $this->data->getUsername();
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\Notification>|\App\Entity\Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\Notification>|\App\Entity\Notification[]
     *
     * @return \App\Entity\User
     */
    public function setNotifications(iterable $notifications): User
    {
        if (is_array($notifications)) {
            $notifications = new ArrayCollection($notifications);
        }

        $this->notifications = $notifications;

        return $this;
    }

    public function addNotification(Notification $notification): User
    {
        $this->notifications->add($notification);

        return $this;
    }

    public function removeNotification(Notification $notification): User
    {
        $this->notifications->removeElement($notification);

        return $this;
    }

    public function getProfileImage(): ProfileImage
    {
        return $this->profileImage;
    }

    public function setProfileImage(ProfileImage $profileImage): User
    {
        $this->profileImage = $profileImage;

        $profileImage->setUser($this);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\SharedFile>|\App\Entity\SharedFile[]
     */
    public function getSharedFiles(): Collection
    {
        return $this->sharedFiles;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\SharedFile>|\App\Entity\SharedFile[]
     *
     * @return \App\Entity\User
     */
    public function setSharedFiles(iterable $sharedFiles): User
    {
        if (is_array($sharedFiles)) {
            $sharedFiles = new ArrayCollection($sharedFiles);
        }

        $this->sharedFiles = $sharedFiles;

        return $this;
    }

    public function addSharedFile(SharedFile $sharedFile): User
    {
        $this->sharedFiles->add($sharedFile);

        return $this;
    }

    public function removeSharedFile(SharedFile $sharedFile): User
    {
        $this->sharedFiles->removeElement($sharedFile);

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
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\SharedNote>|\App\Entity\SharedNote[]
     *
     * @return \App\Entity\User
     */
    public function setSharedNotes(iterable $sharedNotes): User
    {
        if (is_array($sharedNotes)) {
            $sharedNotes = new ArrayCollection($sharedNotes);
        }

        $this->sharedNotes = $sharedNotes;

        return $this;
    }

    public function addSharedNote(SharedNote $sharedNote): User
    {
        $this->sharedNotes->add($sharedNote);

        return $this;
    }

    public function removeSharedNote(SharedNote $sharedNote): User
    {
        $this->sharedNotes->removeElement($sharedNote);

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
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\SharedReminder>|\App\Entity\SharedReminder[]
     *
     * @return \App\Entity\User
     */
    public function setSharedReminders(iterable $sharedReminders): User
    {
        if (is_array($sharedReminders)) {
            $sharedReminders = new ArrayCollection($sharedReminders);
        }

        $this->sharedReminders = $sharedReminders;

        return $this;
    }

    public function addSharedReminder(SharedReminder $sharedReminder): User
    {
        $this->sharedReminders->add($sharedReminder);

        return $this;
    }

    public function removeSharedReminder(SharedReminder $sharedReminder): User
    {
        $this->sharedReminders->removeElement($sharedReminder);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\LoginToken>|\App\Entity\LoginToken[]
     */
    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\LoginToken>|\App\Entity\LoginToken[]
     *
     * @return \App\Entity\User
     */
    public function setTokens(iterable $tokens): User
    {
        if (is_array($tokens)) {
            $tokens = new ArrayCollection($tokens);
        }

        $this->tokens = $tokens;

        return $this;
    }

    public function addToken(LoginToken $token): User
    {
        $this->tokens->add($token);

        return $this;
    }

    public function removeToken(LoginToken $token): User
    {
        $this->tokens->removeElement($token);

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
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\TookUpShare>|\App\Entity\TookUpShare[]
     *
     * @return \App\Entity\User
     */
    public function setTookUpShares(iterable $tookUpShares): User
    {
        if (is_array($tookUpShares)) {
            $tookUpShares = new ArrayCollection($tookUpShares);
        }

        $this->tookUpShares = $tookUpShares;

        return $this;
    }

    public function addTookUpShare(TookUpShare $tookUpShare): User
    {
        $this->tookUpShares->add($tookUpShare);

        return $this;
    }

    public function removeTookUpShare(TookUpShare $tookUpShare): User
    {
        $this->tookUpShares->removeElement($tookUpShare);

        return $this;
    }

    public function getData(): \App\Entity\UserData
    {
        return $this->data;
    }

    public function setData(\App\Entity\UserData $data): User
    {
        $this->data = $data;

        $this->data->setUser($this);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\PrivateFile>|\App\Entity\PrivateFile[]
     */
    public function getPrivateFiles(): Collection
    {
        return $this->privateFiles;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\PrivateFile>|\App\Entity\PrivateFile[]
     *
     * @return \App\Entity\User
     */
    public function setPrivateFiles(iterable $privateFiles): User
    {
        if (is_array($privateFiles)) {
            $privateFiles = new ArrayCollection($privateFiles);
        }

        $this->privateFiles = $privateFiles;

        return $this;
    }

    public function addUserFile(PrivateFile $privateFile): User
    {
        $this->privateFiles->add($privateFile);

        return $this;
    }

    public function removeUserFile(PrivateFile $privateFile): User
    {
        $this->privateFiles->removeElement($privateFile);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\PrivateNote>|\App\Entity\PrivateNote[]
     */
    public function getPrivateNotes(): Collection
    {
        return $this->privateNotes;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\PrivateNote>|\App\Entity\PrivateNote[]
     *
     * @return \App\Entity\User
     */
    public function setPrivateNotes(iterable $privateNotes): User
    {
        if (is_array($privateNotes)) {
            $privateNotes = new ArrayCollection($privateNotes);
        }

        $this->privateNotes = $privateNotes;

        return $this;
    }

    public function addUserNote(PrivateNote $privateNote): User
    {
        $this->privateNotes->add($privateNote);

        return $this;
    }

    public function removeUserNote(PrivateNote $privateNote): User
    {
        $this->privateNotes->removeElement($privateNote);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\PrivateReminder>|\App\Entity\PrivateReminder[]
     */
    public function getPrivateReminders(): Collection
    {
        return $this->privateReminders;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\PrivateReminder>|\App\Entity\PrivateReminder[] $privateReminders
     *
     * @return \App\Entity\User
     */
    public function setPrivateReminders(iterable $privateReminders): User
    {
        if (is_array($privateReminders)) {
            $privateReminders = new ArrayCollection($privateReminders);
        }

        $this->privateReminders = $privateReminders;

        return $this;
    }

    public function addPrivateReminder(PrivateReminder $privateReminder): User
    {
        $this->privateReminders->add($privateReminder);

        return $this;
    }

    public function removePrivateReminder(PrivateReminder $privateReminder): User
    {
        $this->privateReminders->removeElement($privateReminder);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\ClassGroup>|\App\Entity\ClassGroup[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\ClassGroup>|\App\Entity\ClassGroup[] $groups
     *
     * @return User
     */
    public function setGroups(Collection $groups): User
    {
        $this->groups = $groups;

        foreach ($groups as $group) {
            $group->addUser($this);
        }

        return $this;
    }

    public function addGroup(ClassGroup $group): User
    {
        if ($this->groups->contains($group)) {
            return $this;
        }

        $this->groups->add($group);
        $group->addUser($this);

        return $this;
    }

    public function removeGroup(ClassGroup $group): User
    {
        if (!$this->groups->contains($group)) {
            return $this;
        }

        $this->groups->removeElement($group);
        $group->removeUser($this);

        return $this;
    }
}
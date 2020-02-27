<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Private file or folder (access only to owner)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="user_files", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_user_files_user_name_path", columns={"name", "path", "user_id"})
 * })
 * @ORM\Entity
 */
class PrivateFile
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="user_file_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var string Name
     * @ORM\Column(name="name", type="string", length=50, nullable=false, options={  })
     */
    private string $name;
    /**
     * @var string FTP path
     * @ORM\Column(name="path", type="string", length=100, nullable=false, options={  })
     */
    private string $path;
    /**
     * @var bool Is it folder?
     * @ORM\Column(name="folder", type="boolean", length=1, nullable=false, options={  })
     */
    private bool $folder;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="privateFiles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false, onDelete="CASCADE")
     * @var \App\Entity\User Owner
     */
    private User $owner;

    /**
     * @ORM\OneToMany(targetEntity="SharedFile", mappedBy="file")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SharedFile>
     */
    private Collection $sharedFiles;

    public function __construct()
    {
        $this->sharedFiles = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): PrivateFile
    {
        $this->id = $id;

        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): PrivateFile
    {
        $this->owner = $owner;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): PrivateFile
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): PrivateFile
    {
        $this->path = $path;

        return $this;
    }

    public function isFolder(): bool
    {
        return $this->folder;
    }

    public function setFolder(bool $folder): PrivateFile
    {
        $this->folder = $folder;

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
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\SharedFile>|\App\Entity\SharedFile[] $sharedFiles
     *
     * @return \App\Entity\PrivateFile
     */
    public function setSharedFiles(iterable $sharedFiles): PrivateFile
    {
        if (is_array($sharedFiles)) {
            $sharedFiles = new ArrayCollection($sharedFiles);
        }

        $this->sharedFiles = $sharedFiles;

        return $this;
    }

    public function addSharedFiles(SharedFile $sharedFile): PrivateFile
    {
        $this->sharedFiles->add($sharedFile);

        return $this;
    }

    public function removeSharedFile(SharedFile $sharedFile): PrivateFile
    {
        $this->sharedFiles->removeElement($sharedFile);

        return $this;
    }
}
<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function end;
use function explode;
use function in_array;
use function is_array;

/**
 * Private file or folder (access only to owner)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="user_files", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uq_user_files_user_name_parent", columns={"name", "parent_id", "user_id"})
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
    private ?int $id = null;
    /**
     * @var string Name
     * @ORM\Column(name="name", type="string", length=50, nullable=false, options={  })
     */
    private string $name;
    /**
     * @var bool Is it folder?
     * @ORM\Column(name="folder", type="boolean", length=1, nullable=false, options={ "default": 0 })
     */
    private bool $folder;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="privateFiles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false, onDelete="CASCADE")
     * @var \App\Entity\User Owner
     */
    private User $owner;
    /**
     * @var \App\Entity\PrivateFile|null Parent file (folder resp.)
     * @ORM\ManyToOne(targetEntity="PrivateFile", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="user_file_id", onDelete="CASCADE")
     */
    private ?PrivateFile $parent = null;

    /**
     * @ORM\OneToMany(targetEntity="PrivateFile", mappedBy="parent")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\PrivateFile>
     */
    private Collection $children;
    /**
     * @ORM\OneToMany(targetEntity="SharedFile", mappedBy="file")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SharedFile>
     */
    private Collection $sharedFiles;

    public function __construct()
    {
        $this->children = new ArrayCollection;
        $this->sharedFiles = new ArrayCollection;
    }

    /**
     * Returns file type from its name
     *
     * @return string|null File type (inter-project name, not extension)
     */
    public function getType(): ?string
    {
        if($this->isFolder()) {
            return null;
        }

        $fileTypes = [
            'text-document' => ["txt", "doc", "docx", "rtf", "odt", "wpd", "wks", "wps"],
            'sheet-document' => ["ods", "xlr", "xls", "xlsx"],
            'presentation-document' => ["key", "odp", "pps", "ppt", "pptx"],
            'pdf' => ["pdf"],
            'php' => ["php", "phtml", "php5"],
            'html' => ["html", "htm", "xhtml"],
            'css' => ["css", "less", "scss", "sass", "styl"],
            'js' => ["js", "ts"],
            'source-code' => ["c", "class", "cpp", "cs", "h", "java", "sh", "bat", "swift", "vb", "py", "xml", "jsp", "cgi", "pl", "cfm", "asp", "aspx"],
            'executable' => ["exe", "apk", "bin", "com", "gadget", "jar", "wsf"],
            'archive' => ["7z", "arj", "deb", "pkg", "rar", "rpm", "gz", "z", "zip"],
            'database' => ["sql", "csv", "dat", "db", "dbf", "log", "mdb", "sav", "tar"],
            'font' => ["fnt", "fon", "otf", "ttf", "woff", "woff2", "eot"],
            'image' => ["ai", "bmp", "gif", "ico", "jpeg", "jpg", "png", "ps", "psd", "svg", "tif", "tiff"],
            'audio' => ["aif", "cda", "mid", "midi", "mp3", "mpa", "ogg", "wav", "wma", "wpl"],
            'video' => ["3g2", "3gp", "avi", "flv", "h264", "mkv", "mov", "mp4", "mpg", "mpeg", "rm", "swf", "vob", "wmv"]
        ];

        $fileNameParts = explode(".", $this->getName());
        $extension = end($fileNameParts);

        foreach ($fileTypes as $typeName => $typeExtensions) {
            if (in_array($extension, $typeExtensions)) {
                return $typeName;
            }
        }

        return "general";
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

    public function getParent(): ?PrivateFile
    {
        return $this->parent;
    }

    public function setParent(?PrivateFile $parent): PrivateFile
    {
        $this->parent = $parent;

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

    public function addChild(PrivateFile $privateFile): PrivateFile
    {
        $this->children->add($privateFile);

        return $this;
    }

    public function removeChild(PrivateFile $privateFile): PrivateFile
    {
        $this->children->removeElement($privateFile);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\PrivateFile>|\App\Entity\PrivateFile[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\PrivateFile>|\App\Entity\PrivateFile[] $children
     *
     * @return \App\Entity\PrivateFile
     */
    public function setChildren(iterable $children): PrivateFile
    {
        if (is_array($children)) {
            $children = new ArrayCollection($children);
        }

        $this->children = $children;

        return $this;
    }

    public function addSharedFile(SharedFile $sharedFile): PrivateFile
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
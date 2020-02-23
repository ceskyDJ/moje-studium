<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;

/**
 * User's file or folder
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class File
{
    /**
     * Owner shared this with his class
     */
    const SHARED_WITH_CLASS = 0;
    /**
     * Owner shared this with logged in user
     */
    const SHARED_WITH_ME = 1;

    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\User Owner
     */
    private User $owner;
    /**
     * @var string Name
     */
    private string $name;
    /**
     * @var string FTP path
     */
    private string $path;
    /**
     * @var bool Is it folder?
     */
    private bool $folder;
    /**
     * @var \DateTime When was it shared?
     */
    private ?DateTime $shared;
    /**
     * @var int How is it shared with the logged in user?
     */
    private ?int $shareType;

    /**
     * File constructor
     *
     * @param int $id
     * @param \App\Entity\User $owner
     * @param string $name
     * @param string $path
     * @param bool $folder
     * @param \DateTime|null $shared
     * @param int|null $shareType
     */
    public function __construct(
        int $id,
        User $owner,
        string $name,
        string $path,
        bool $folder,
        ?DateTime $shared,
        ?int $shareType
    ) {
        $this->id = $id;
        $this->owner = $owner;
        $this->name = $name;
        $this->path = $path;
        $this->folder = $folder;
        $this->shared = $shared;
        $this->shareType = $shareType;
    }

    /**
     * Getter for id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Fluent setter for id
     *
     * @param int $id
     *
     * @return File
     */
    public function setId(int $id): File
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for owner
     *
     * @return \App\Entity\User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * Fluent setter for owner
     *
     * @param \App\Entity\User $owner
     *
     * @return File
     */
    public function setOwner(User $owner): File
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Getter for name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Fluent setter for name
     *
     * @param string $name
     *
     * @return File
     */
    public function setName(string $name): File
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Fluent setter for path
     *
     * @param string $path
     *
     * @return File
     */
    public function setPath(string $path): File
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Getter for folder
     *
     * @return bool
     */
    public function isFolder(): bool
    {
        return $this->folder;
    }

    /**
     * Fluent setter for folder
     *
     * @param bool $folder
     *
     * @return File
     */
    public function setFolder(bool $folder): File
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Getter for shared
     *
     * @return \DateTime
     */
    public function getShared(): DateTime
    {
        return $this->shared;
    }

    /**
     * Fluent setter for shared
     *
     * @param \DateTime $shared
     *
     * @return File
     */
    public function setShared(DateTime $shared): File
    {
        $this->shared = $shared;

        return $this;
    }

    /**
     * Getter for shareType
     *
     * @return int
     */
    public function getShareType(): int
    {
        return $this->shareType;
    }

    /**
     * Fluent setter for shareType
     *
     * @param int $shareType
     *
     * @return File
     */
    public function setShareType(int $shareType): File
    {
        $this->shareType = $shareType;

        return $this;
    }
}
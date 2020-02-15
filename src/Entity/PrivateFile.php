<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Private file or folder (access only to owner)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class PrivateFile
{
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
     * PrivateFile constructor
     *
     * @param int $id
     * @param \App\Entity\User $user
     * @param string $name
     * @param string $path
     * @param bool $folder
     */
    public function __construct(int $id, User $user, string $name, string $path, bool $folder)
    {
        $this->id = $id;
        $this->owner = $user;
        $this->name = $name;
        $this->path = $path;
        $this->folder = $folder;
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
     * @return PrivateFile
     */
    public function setId(int $id): PrivateFile
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
     * @return PrivateFile
     */
    public function setOwner(User $owner): PrivateFile
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
     * @return PrivateFile
     */
    public function setName(string $name): PrivateFile
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
     * @return PrivateFile
     */
    public function setPath(string $path): PrivateFile
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
     * @return PrivateFile
     */
    public function setFolder(bool $folder): PrivateFile
    {
        $this->folder = $folder;

        return $this;
    }
}
<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\PrivateFile;
use App\Entity\SchoolClass;
use App\Entity\SharedFile;
use App\Entity\User;

/**
 * Repository for files
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface IFileRepository
{
    /**
     * Finds private file by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\PrivateFile
     */
    public function getById(int $id): PrivateFile;

    /**
     * Finds shared file by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\SharedFile
     */
    public function getSharedById(int $id): SharedFile;

    /**
     * Adds new file or folder
     *
     * @param \App\Entity\User $owner
     * @param string $name
     * @param string $path
     * @param bool $folder
     */
    public function add(User $owner, string $name, string $path, bool $folder): void;

    /**
     * Deletes existing file or folder
     *
     * @param int $id
     */
    public function delete(int $id): void;

    /**
     * Edits file or folder
     *
     * @param int $id
     * @param string $name
     * @param string $path
     */
    public function edit(int $id, string $name, string $path): void;

    /**
     * Shares file or folder
     *
     * @param int $id
     * @param \App\Entity\User|null $targetUser
     * @param \App\Entity\SchoolClass|null $targetClass
     */
    public function share(int $id, ?User $targetUser, ?SchoolClass $targetClass = null): void;
}
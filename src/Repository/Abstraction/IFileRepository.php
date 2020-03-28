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
     * Finds all private files owned by user
     *
     * @param \App\Entity\User $user
     * @param \App\Entity\PrivateFile|null $parent
     *
     * @return array
     */
    public function getByOwnerAndParent(User $user, ?PrivateFile $parent = null): array;

    /**
     * Finds private file by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\PrivateFile
     */
    public function getById(int $id): PrivateFile;

    /**
     * Finds private file by its owner, parent folder and name
     *
     * @param \App\Entity\User $owner
     * @param \App\Entity\PrivateFile|null $parent
     * @param string $name
     *
     * @return \App\Entity\PrivateFile|null
     */
    public function getByOwnerParentAndName(User $owner, ?PrivateFile $parent, string $name): ?PrivateFile;

    /**
     * Returns $limit files shared with user or class where the user is
     *
     * @param \App\Entity\User $targetUser
     * @param int|null $limit Maximum count of records
     *
     * @return \App\Entity\SharedFile[]
     */
    public function getSharedByUserOrItsClassWithLimit(User $targetUser, ?int $limit = null): array;

    /**
     * Finds shared file by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\SharedFile
     */
    public function getSharedById(int $id): SharedFile;

    /**
     * Returns file's child folders
     *
     * @param \App\Entity\User $owner
     * @param int|null $id
     *
     * @return array Child folders
     */
    public function getChildFolders(User $owner, ?int $id): array;

    /**
     * Adds new file or folder
     *
     * @param \App\Entity\User $owner
     * @param \App\Entity\PrivateFile|null $parent
     * @param string $name
     * @param bool $folder
     *
     * @return \App\Entity\PrivateFile
     */
    public function add(User $owner, ?PrivateFile $parent, string $name, bool $folder): PrivateFile;

    /**
     * Deletes existing file or folder
     *
     * @param int $id
     */
    public function delete(int $id): void;

    /**
     * Renames file or folder
     *
     * @param int $id
     * @param string $name
     */
    public function rename(int $id, string $name): void;

    /**
     * Moves file to new location (sets new parent resp.)
     *
     * @param int $id
     * @param \App\Entity\PrivateFile|null $target New parent
     */
    public function move(int $id, ?PrivateFile $target): void;

    /**
     * Shares file or folder
     *
     * @param int $id
     * @param \App\Entity\User|null $targetUser
     * @param \App\Entity\SchoolClass|null $targetClass
     */
    public function share(int $id, ?User $targetUser = null, ?SchoolClass $targetClass = null): void;

    /**
     * Cancels all shares of the file
     *
     * @param int $id
     */
    public function cancelShare(int $id): void;
}
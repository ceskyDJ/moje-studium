<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\PrivateFile;
use App\Entity\SchoolClass;
use App\Entity\SharedFile;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Mammoth\Database\DB;
use Mammoth\DI\DIClass;
use Tracy\Debugger;

/**
 * Repository for files
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBFileRepository implements Abstraction\IFileRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function getByOwnerAndParent(User $user, ?PrivateFile $parent = null): array
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT f
            FROM App\Entity\PrivateFile f
            WHERE f.owner = :user AND (f.parent = :parent OR (:parent IS NULL AND f.parent IS NULL)) 
        ");
        $query->setParameter("user", $user);
        $query->setParameter("parent", $parent);

        return $query->getResult();
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): PrivateFile
    {
        /**
         * @var $file PrivateFile
         */
        $file = $this->em->find(PrivateFile::class, $id);

        return $file;
    }

    /**
     * @inheritDoc
     */
    public function getByOwnerParentAndName(User $owner, ?PrivateFile $parent, string $name): ?PrivateFile
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT f
            FROM App\Entity\PrivateFile f
            WHERE f.owner = :owner AND (f.parent = :parent OR (:parent IS NULL AND f.parent IS NULL)) AND f.name = :name
        ");

        $query->setParameter("owner", $owner);
        $query->setParameter("parent", $parent);
        $query->setParameter("name", $name);

        return $query->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function getSharedByUserOrItsClassWithLimit(User $targetUser, ?int $limit = null): array
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT sf
            FROM App\Entity\SharedFile sf
            LEFT JOIN App\Entity\SchoolClass c WITH c = sf.targetClass
            JOIN App\Entity\PrivateFile f WITH f = sf.file
            WHERE (sf.targetUser = :user OR :user MEMBER OF c.users) AND f.owner != :user
            ORDER BY sf.shared DESC
        ");

        $query->setParameter("user", $targetUser);

        if ($limit !== null) {
            $query->setMaxResults($limit);
        }

        return $query->getResult();
    }

    /**
     * @inheritDoc
     */
    public function getSharedById(int $id): SharedFile
    {
        /**
         * @var $sharedFile SharedFile
         */
        $sharedFile = $this->em->find(SharedFile::class, $id);

        return $sharedFile;
    }

    /**
     * @inheritDoc
     */
    public function getChildFolders(User $owner, ?int $id): array
    {
        $file = $id !== null ? $this->getById($id) : null;

        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT f
            FROM App\Entity\PrivateFile f
            WHERE f.owner = :owner AND f.folder = TRUE AND (f.parent = :parent OR (:parent IS NULL AND f.parent IS NULL)) 
        ");
        $query->setParameter("owner", $owner);
        $query->setParameter("parent", $file);

        return $query->getResult();
    }

    /**
     * @inheritDoc
     */
    public function add(User $owner, ?PrivateFile $parent, string $name, bool $folder): PrivateFile
    {
        $file = new PrivateFile;
        $file->setOwner($owner)->setParent($parent)->setName($name)->setFolder($folder);

        $this->em->persist($file);
        $this->em->flush();

        return $file;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->em->remove($this->getById($id));
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function rename(int $id, string $name): void
    {
        $file = $this->getById($id);
        $file->setName($name);

        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function move(int $id, ?PrivateFile $target): void
    {
        $file = $this->getById($id);
        $file->setParent($target);

        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function share(int $id, ?User $targetUser, ?SchoolClass $targetClass = null): void
    {
        $sharedFile = new SharedFile;
        $sharedFile->setFile($this->getById($id))->setTargetUser($targetUser)->setTargetClass($targetClass);

        $this->em->persist($sharedFile);
        $this->em->flush();
    }
}
<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\PrivateFile;
use App\Entity\SchoolClass;
use App\Entity\SharedFile;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

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
    public function add(User $owner, string $name, string $path, bool $folder): PrivateFile
    {
        $file = new PrivateFile;
        $file->setOwner($owner)->setName($name)->setPath($path)->setFolder($folder);

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
    public function edit(int $id, string $name, string $path): void
    {
        $file = $this->getById($id);
        $file->setName($name)->setPath($path);

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
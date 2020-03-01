<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\PrivateNote;
use App\Entity\SchoolClass;
use App\Entity\SharedNote;
use App\Entity\TookUpShare;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for notes
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBNoteRepository implements Abstraction\INoteRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): PrivateNote
    {
        /**
         * @var $note PrivateNote
         */
        $note = $this->em->find(PrivateNote::class, $id);

        return $note;
    }

    /**
     * @inheritDoc
     */
    public function getSharedById(int $id): SharedNote
    {
        /**
         * @var $sharedNote SharedNote
         */
        $sharedNote = $this->em->find(SharedNote::class, $id);

        return $sharedNote;
    }

    /**
     * @inheritDoc
     */
    public function add(User $owner, string $content): PrivateNote
    {
        $note = new PrivateNote;
        $note->setOwner($owner)->setContent($content);

        $this->em->persist($note);
        $this->em->flush();

        return $note;
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
    public function edit(int $id, string $content): void
    {
        $note = $this->getById($id);
        $note->setContent($content);

        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function share(int $id, ?User $targetUser, ?SchoolClass $targetClass = null): void
    {
        $sharedNote = new SharedNote;
        $sharedNote->setNote($this->getById($id))->setTargetUser($targetUser)->setTargetClass($targetClass);

        $this->em->persist($sharedNote);
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function takeUp(User $user, int $id): void
    {
        $tookUpShare = new TookUpShare;
        $tookUpShare->setUser($user)->setSharedNote($this->getSharedById($id));

        $this->em->persist($tookUpShare);
        $this->em->flush();
    }
}
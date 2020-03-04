<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\PrivateReminder;
use App\Entity\SchoolClass;
use App\Entity\SchoolSubject;
use App\Entity\SharedReminder;
use App\Entity\TookUpShare;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for reminders
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBReminderRepository implements Abstraction\IReminderRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): PrivateReminder
    {
        /**
         * @var $reminder PrivateReminder
         */
        $reminder = $this->em->find(PrivateReminder::class, $id);

        return $reminder;
    }

    /**
     * @inheritDoc
     */
    public function getSharedByUserOrItsClassWithLimit(User $targetUser, int $limit): array
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT sr
            FROM App\Entity\SharedReminder sr
            LEFT JOIN App\Entity\SchoolClass c WITH c = sr.targetClass
            JOIN App\Entity\PrivateReminder r WITH r = sr.reminder
            WHERE (sr.targetUser = :user OR :user MEMBER OF c.users) AND r.owner != :user
            ORDER BY sr.shared DESC
        ");

        $query->setMaxResults($limit)
            ->setParameter("user", $targetUser);

        return $query->getResult();
    }

    /**
     * @inheritDoc
     */
    public function getSharedById(int $id): SharedReminder
    {
        /**
         * @var $sharedReminder SharedReminder
         */
        $sharedReminder = $this->em->find(SharedReminder::class, $id);

        return $sharedReminder;
    }

    /**
     * @inheritDoc
     */
    public function add(User $owner, string $type, string $content, DateTime $when, SchoolSubject $subject): PrivateReminder
    {
        $reminder = new PrivateReminder;
        $reminder->setOwner($owner)->setType($type)->setContent($content)->setWhen($when)->setSubject($subject);

        $this->em->persist($reminder);
        $this->em->flush();

        return $reminder;
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
    public function edit(
        int $id,
        User $owner,
        string $type,
        string $content,
        DateTime $when,
        SchoolSubject $subject
    ): void {
        $reminder = $this->getById($id);
        $reminder->setOwner($owner)->setType($type)->setContent($content)->setWhen($when)->setSubject($subject);

        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function share(int $id, ?User $targetUser, ?SchoolClass $targetClass = null): void
    {
        $sharedReminder = new SharedReminder;
        $sharedReminder->setReminder($this->getById($id))->setTargetUser($targetUser)->setTargetClass($targetClass);

        $this->em->persist($sharedReminder);
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function takeUp(User $user, int $id): void
    {
        $tookUpShare = new TookUpShare;
        $tookUpShare->setUser($user)->setSharedReminder($this->getSharedById($id));

        $this->em->persist($tookUpShare);
        $this->em->flush();
    }
}
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
    public function getByUser(User $user, ?DateTime $from = null, ?DateTime $to = null): array
    {
        $year = (int)date("Y");
        $weekNumber = (int)date("W");

        $from = $from ?? (new DateTime())->setISODate($year, $weekNumber);
        $to = $to ?? (new DateTime())->setISODate($year, $weekNumber, 7);

        $query = $this->em->createQuery(
        /** @lang DQL */ "
            SELECT r
            FROM App\Entity\PrivateReminder r
            LEFT JOIN App\Entity\SharedReminder sr WITH sr.reminder = r
            LEFT JOIN App\Entity\TookUpShare ts WITH ts.sharedReminder = sr
            WHERE (r.owner = :user OR ts.user = :user) AND (r.when BETWEEN :from AND :to)
            ORDER BY r.when
        "
        );
        $query->setParameter("user", $user);
        $query->setParameter("from", $from);
        $query->setParameter("to", $to);

        $reminders = $query->getResult();

        $lastItem = null;
        $uniqueReminders = []; // without duplicity school events
        /**
         * @var $reminder \App\Entity\PrivateReminder
         * @var $lastItem \App\Entity\PrivateReminder
         */
        foreach ($reminders as $reminder) {
            if ($lastItem !== null && $reminder->getType() === "school-event"
                && $reminder->getWhen()->getTimestamp() === $lastItem->getWhen()->getTimestamp()) {
                continue;
            }

            $uniqueReminders[] = $reminder;
            $lastItem = $reminder;
        }

        return $uniqueReminders;
    }

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
    public function getSharedByUserOrItsClassWithLimit(User $targetUser, ?int $limit = null): array
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT sr
            FROM App\Entity\SharedReminder sr
            LEFT JOIN App\Entity\SchoolClass c WITH c = sr.targetClass
            JOIN App\Entity\PrivateReminder r WITH r = sr.reminder
            WHERE (sr.targetUser = :user OR :user MEMBER OF c.users) AND r.owner != :user
            ORDER BY sr.shared DESC
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

    /**
     * @inheritDoc
     */
    public function cancelShare(int $id): void
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            DELETE FROM App\Entity\SharedReminder sr WHERE sr.reminder = :reminder
        ");
        $query->setParameter("reminder", $this->getById($id));

        $query->execute();
    }
}
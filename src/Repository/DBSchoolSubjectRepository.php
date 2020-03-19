<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\SchoolClass;
use App\Entity\SchoolSubject;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for school subjects
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBSchoolSubjectRepository implements Abstraction\ISchoolSubjectRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getByUser(User $user): array
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT s
            FROM App\Entity\SchoolSubject s
            JOIN App\Entity\TaughtGroup tg WITH tg MEMBER OF s.taughtGroups
            JOIN App\Entity\ClassGroup g WITH tg.group = g
            JOIN App\Entity\User u WITH g MEMBER OF u.groups 
            WHERE u = :user
            ORDER BY s.shortcut
        ");
        $query->setParameter("user", $user);

        return $query->getResult();
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): SchoolSubject
    {
        /**
         * @var $subject SchoolSubject
         */
        $subject = $this->em->find(SchoolSubject::class, $id);

        return $subject;
    }

    /**
     * @inheritDoc
     */
    public function getByClassAndShortcut(SchoolClass $class, string $shortcut): SchoolSubject
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT s FROM App\Entity\SchoolSubject s WHERE s.class = :class AND s.shortcut = :shortcut
        ");
        $query->setParameter("class", $class);
        $query->setParameter("shortcut", $shortcut);

        return $query->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function add(SchoolClass $class, string $name, string $shortcut): SchoolSubject
    {
        $subject = new SchoolSubject;
        $subject->setClass($class)->setName($name)->setShortcut($shortcut);

        $this->em->persist($subject);
        $this->em->flush();

        return $subject;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->em->remove($this->getById($id));
        $this->em->flush();
    }
}
<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Rank;
use App\Entity\SchoolClass;
use App\Entity\User;
use App\Entity\UserData;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for users
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBUserRepository implements Abstraction\IUserRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): User
    {
        /**
         * @var $user User
         */
        $user = $this->em->find(User::class, $id);

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function getByUsernameOrEmail(string $usernameOrEmail): ?User
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT u FROM App\Entity\User u JOIN App\Entity\UserData d WHERE d.email = :email OR d.username = :username
        ");
        $query->setParameter("email", $usernameOrEmail);
        $query->setParameter("username", $usernameOrEmail);

        return $query->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function add(
        string $username,
        string $password,
        Rank $rank,
        string $firstName,
        string $lastName,
        string $email
    ): void {
        $user = new User;
        $user->setPassword($password)->setRank($rank);

        $this->em->persist($user);

        $data = new UserData;
        $data->setUser($user)->setUsername($username)->setEmail($email)->setFirstName($firstName)->setLastName(
            $lastName
        );

        $user->setData($data);

        $this->em->persist($data);
        $this->em->flush();
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
        string $username,
        string $password,
        Rank $rank,
        ?SchoolClass $class,
        string $firstName,
        string $lastName,
        string $email
    ): void
    {
        $user = $this->getById($id);
        $user->setPassword($password)->setRank($rank);

        $data = $user->getData();
        $data->setUser($user)->setUsername($username)->setEmail($email)->setFirstName($firstName)->setLastName(
            $lastName
        );

        $this->em->flush();
    }
}
<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ProfileImage;
use App\Entity\Rank;
use App\Entity\SchoolClass;
use App\Entity\User;
use App\Entity\UserData;
use App\Repository\Abstraction\IProfileIconRepository;
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
     * @inject
     */
    private IProfileIconRepository $profileIconRepository;

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->em->getRepository(User::class)->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getByClass(SchoolClass $class): array
    {
        $query = $this->em->createQuery(/** @lang DQL */ "
            SELECT u FROM App\Entity\User u WHERE u.class = :class
        ");
        $query->setParameter("class", $class);

        return $query->getResult();
    }

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
            SELECT u FROM App\Entity\User u JOIN u.data d WHERE d.email = :email OR d.username = :username
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
    ): User {
        $user = new User;
        $user->setPassword($password)->setRank($rank);

        $this->em->persist($user);

        $data = new UserData;
        $data->setUser($user)->setUsername($username)->setEmail($email)->setFirstName($firstName)->setLastName(
            $lastName
        );

        $user->setData($data);
        $this->em->persist($data);

        // New user automatically gets default profile image
        $profileImage = new ProfileImage;
        $profileImage->setUser($user)->setIcon($this->profileIconRepository->getById(1));

        $user->setProfileImage($profileImage);
        $this->em->persist($profileImage);
        $this->em->flush();

        return $user;
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
        ?SchoolClass $class,
        string $firstName,
        string $lastName,
        string $email
    ): void
    {
        $user = $this->getById($id);
        $user->setPassword($password);

        $data = $user->getData();
        $data->setUser($user)->setUsername($username)->setEmail($email)->setFirstName($firstName)->setLastName(
            $lastName
        );

        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function changeRank(int $id, Rank $rank): void
    {
        $user = $this->getById($id);
        $user->setRank($rank);

        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function confirm(int $id): void
    {
        $user = $this->getById($id);
        $user->setConfirmed(true);

        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function completeFirstLogin(int $id): void
    {
        $user = $this->getById($id);
        $user->setFirstLogin(false);

        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function selectClass(int $id, SchoolClass $class): void
    {
        $user = $this->getById($id);
        $user->setClass($class);

        $this->em->flush();
    }
}
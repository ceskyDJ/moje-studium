<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\LoginToken;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Repository for login keys
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class DBLoginTokenRepository implements Abstraction\ILoginTokenRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): LoginToken
    {
        /**
         * @var $token LoginToken
         */
        $token = $this->em->find(LoginToken::class, $id);

        return $token;
    }

    /**
     * @inheritDoc
     */
    public function getByContent(string $content): LoginToken
    {
        $query = $this->em->createQuery(/** @lang DQL */ "SELECT t FROM App\Entity\LoginToken t WHERE t.content = ?1");
        $query->setParameter(1, $content);

        return $query->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function add(User $user, string $content): void
    {
        $token = new LoginToken;
        $token->setUser($user)->setContent($content);

        $this->em->persist($token);
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function deactivate(int $id): void
    {
        $token = $this->getById($id);
        $token->setValid(false);

        $this->em->flush();
    }
}
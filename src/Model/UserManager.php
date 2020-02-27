<?php

declare(strict_types = 1);

namespace App\Model;

use App\Repository\Abstraction\IUserRepository;
use Mammoth\DI\DIClass;
use Mammoth\Exceptions\NonExistingKeyException;
use Mammoth\Http\Entity\Session;
use Mammoth\Security\Entity\IUser;
use Mammoth\Templates\Abstraction\IMessageManager;
use Mammoth\Url\Abstraction\IRouter;
use function bdump;
use function dump;
use function password_verify;

/**
 * App's own user manager
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Model
 */
class UserManager extends \Mammoth\Security\UserManager
{
    use DIClass;

    /**
     * @inject
     */
    private IMessageManager $messageManager;
    /**
     * @inject
     */
    private IUserRepository $userRepository;
    /**
     * @inject
     */
    private Session $session;

    /**
     * Logs in user
     *
     * @param string $username
     * @param string $password
     *
     * @return bool Has it been successful?
     */
    public function logIn(string $username, string $password): bool
    {
        if (empty($username) || empty($password)) {
            $this->messageManager->addMessage("Nebyla vyplněna všechna pole", "negative");

            return false;
        }

        if (($user = $this->userRepository->getByUsernameOrEmail($username)) === null || !password_verify($password, $user->getPassword())) {
            $this->messageManager->addMessage("Zadali jste chybnou přezdívku (nebo email) a/nebo heslo", "negative");

            return false;
        }

        $this->logInUserToSystem($user, true);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function logInUserToSystem(IUser $user, ?bool $permanent = false): void
    {
        $this->user = $user;

        if ($permanent === true) {
            $this->session->setSessionItem("user", $user->getId());
        }
    }

    /**
     * @inheritDoc
     */
    public function logInUserAutomatically(): void
    {
        try {
            $userId = (int)$this->session->getSessionItemByKey("user");

            $user = $this->userRepository->getById($userId);
            $this->logInUserToSystem($user);
        } catch (NonExistingKeyException $e) {
        }
    }

    /**
     * @inheritDoc
     */
    public function logOutUserFromSystem(): void
    {
        $this->user = null;

        $this->session->deleteSessionItem("user");
    }
}
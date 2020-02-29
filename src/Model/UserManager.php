<?php

declare(strict_types = 1);

namespace App\Model;

use App\Entity\User;
use App\Repository\Abstraction\ILoginTokenRepository;
use App\Repository\Abstraction\IRankRepository;
use App\Repository\Abstraction\IUserRepository;
use Mammoth\DI\DIClass;
use Mammoth\Exceptions\NonExistingKeyException;
use Mammoth\Http\Entity\Server;
use Mammoth\Http\Entity\Session;
use Mammoth\Mailing\Abstraction\IMailer;
use Mammoth\Security\Entity\IUser;
use Mammoth\Templates\Abstraction\IMessageManager;
use Mammoth\Templates\Abstraction\IPrinter;
use RandomLib;
use SecurityLib\Strength;
use function array_map;
use function bdump;
use function dump;
use function explode;
use function filter_input;
use function implode;
use function mb_strlen;
use function password_hash;
use function password_verify;
use function preg_match;
use function ucfirst;
use const FILTER_VALIDATE_EMAIL;
use const PASSWORD_DEFAULT;

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
     * CSS class name for negative messages
     */
    private const NEGATIVE_MESSAGE = "negative";
    /**
     * CSS class name for positive messages
     */
    private const POSITIVE_MESSAGE = "positive";

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
    private IRankRepository $rankRepository;
    /**
     * @inject
     */
    private ILoginTokenRepository $tokenRepository;
    /**
     * @inject
     */
    private Session $session;
    /**
     * @inject
     */
    private IMailer $mailer;
    /**
     * @inject
     */
    private IPrinter $printer;
    /**
     * @inject
     */
    private RandomLib\Factory $random;
    /**
     * @inject
     */
    private Server $server;

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
            $this->messageManager->addMessage("Nebyla vyplněna všechna pole", self::NEGATIVE_MESSAGE);

            return false;
        }

        if (($user = $this->userRepository->getByUsernameOrEmail($username)) === null
            || !password_verify(
                $password,
                $user->getPassword()
            )) {
            $this->messageManager->addMessage(
                "Zadali jste chybnou přezdívku (nebo email) a/nebo heslo",
                self::NEGATIVE_MESSAGE
            );

            return false;
        }

        if ($user->isConfirmed() === false) {
            $this->messageManager->addMessage("Váš účet zatím nebyl potvrzen. Podívejte se emailové schránky.", self::NEGATIVE_MESSAGE);

            return false;
        }

        $this->logInUserToSystem($user, true);

        return true;
    }

    /**
     * Registers new user
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $nickname
     * @param string $email
     * @param string $password
     * @param string $passwordAgain
     *
     * @return bool Has it been successful?
     */
    public function register(
        string $firstName,
        string $lastName,
        string $nickname,
        string $email,
        string $password,
        string $passwordAgain
    ): bool {
        // Required fields
        if (empty($firstName) || empty($lastName) || empty($nickname) || empty($email) || empty($password)
            || empty($passwordAgain)) {
            $this->messageManager->addMessage("Nebyla vyplněna všechna pole", self::NEGATIVE_MESSAGE);

            return false;
        }

        // Names
        if (mb_strlen($firstName) < 3 || mb_strlen($lastName) < 3) {
            $this->messageManager->addMessage("Zkontrolujte si správnost jména a příjmení", self::NEGATIVE_MESSAGE);

            return false;
        } else {
            $firstName = ucfirst($firstName);

            $lastNameParts = explode(" ", $lastName);
            $lastNameParts = array_map(fn($item) => ucfirst($item), $lastNameParts);
            $lastName = implode(" ", $lastNameParts);
        }

        // Nickname
        if (mb_strlen($nickname) < 3) {
            $this->messageManager->addMessage("Přezdívka by měla být dlouhý alespoň 3 znaky", self::NEGATIVE_MESSAGE);
        }

        // Email
        if (filter_input(FILTER_VALIDATE_EMAIL, $email) === false) {
            $this->messageManager->addMessage("Zkontrolujte si správnost emailové adresy", self::NEGATIVE_MESSAGE);

            return false;
        }

        // Passwords' equal
        if ($password !== $passwordAgain) {
            $this->messageManager->addMessage("Zadaná hesla nejsou shodná", self::NEGATIVE_MESSAGE);

            return false;
        }

        // Password
        if (mb_strlen($password) < 8) {
            $this->messageManager->addMessage("Heslo musí být dlouhé minimálně 8 znaků", self::NEGATIVE_MESSAGE);

            return false;
        }

        if ((bool)preg_match("%[a-z]+%", $password) === false || (bool)preg_match("%[A-Z]+%", $password) === false
            || (bool)preg_match("%\d+%", $password) === false) {
            $this->messageManager->addMessage(
                "Heslo musí obsahovat alespoň jedno malé písemno, velké písmeno a číslici",
                self::NEGATIVE_MESSAGE
            );

            return false;
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        // Unique values
        if ($this->userRepository->getByUsernameOrEmail($nickname) !== null) {
            $this->messageManager->addMessage("Zadaná přezdívka je již používána", self::NEGATIVE_MESSAGE);

            return false;
        }

        if ($this->userRepository->getByUsernameOrEmail($email) !== null) {
            $this->messageManager->addMessage("Zadaný email je již používána", self::NEGATIVE_MESSAGE);

            return false;
        }

        $rank = $this->rankRepository->getDefaultForUsers();

        $this->userRepository->add($nickname, $password, $rank, $firstName, $lastName, $email);

        // Confirmation token
        $token = $this->generateTokenForRegistration($this->userRepository->getByUsernameOrEmail($nickname));

        // Confirmation email
        $link = "{$this->server->getBaseHref()}{$this->server->getUrl()}/confirm/{$token}";
        $link = str_replace("//", "/", $link);

        $message = $this->printer->getFileHTML(__DIR__."/../views/emails/email-confirm.latte", ['link' => $link]);

        $this->mailer->sendMail(
            "Moje Studium",
            "moje-studium@ceskydj.cz",
            [['address' => $email, 'name' => "{$firstName} {$lastName}"]],
            "Potvrzení registrace",
            $message
        );

        return true;
    }

    /**
     * Generates token for registration and saves it
     *
     * @param \App\Entity\User $user Token's owner
     *
     * @return string Generated token
     */
    private function generateTokenForRegistration(User $user): string
    {
        $generator = $this->random->getGenerator(new Strength(Strength::MEDIUM));
        $allowedCharacters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+*-_";
        $token = $generator->generateString(512, $allowedCharacters);

        $this->tokenRepository->add($user, $token);

        return $token;
    }

    /**
     * Confirms user (his address respectively)
     *
     * @param string $tokenContent Generated token sent to user's email address
     *
     * @return bool Was it successful?
     */
    public function confirmUser(string $tokenContent): bool
    {
        if (($token = $this->tokenRepository->getByContent($tokenContent)) === null) {
            $this->messageManager->addMessage("Váš účet již byl potvrzen nebo máte chybný klíč", self::NEGATIVE_MESSAGE);

            return false;
        }

        $this->tokenRepository->deactivate($token->getId());
        $this->userRepository->confirm((int)$token->getUser()->getId());

        $this->messageManager->addMessage("Váš účet byl úspěšně potvrzen. Nyní se již můžete přihlásit", self::POSITIVE_MESSAGE);

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
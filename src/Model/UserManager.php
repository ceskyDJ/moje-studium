<?php

declare(strict_types = 1);

namespace App\Model;

use App\Entity\User;
use App\Repository\Abstraction\IClassSelectionRequestRepository;
use App\Repository\Abstraction\ILoginTokenRepository;
use App\Repository\Abstraction\IProfileIconRepository;
use App\Repository\Abstraction\IRankRepository;
use App\Repository\Abstraction\ISchoolClassRepository;
use App\Repository\Abstraction\ISchoolRepository;
use App\Repository\Abstraction\IUserRepository;
use FilesystemIterator;
use Mammoth\DI\DIClass;
use Mammoth\Exceptions\NonExistingKeyException;
use Mammoth\Http\Entity\Server;
use Mammoth\Http\Entity\Session;
use Mammoth\Mailing\Abstraction\IMailer;
use Mammoth\Security\Entity\IUser;
use Mammoth\Templates\Abstraction\IMessageManager;
use Mammoth\Templates\Abstraction\IPrinter;
use RandomLib;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SecurityLib\Strength;
use function array_map;
use function explode;
use function filter_input;
use function implode;
use function json_encode;
use function mb_strlen;
use function password_hash;
use function password_verify;
use function preg_match;
use function realpath;
use function round;
use function rtrim;
use function strtoupper;
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
    private ISchoolRepository $schoolRepository;
    /**
     * @inject
     */
    private ISchoolClassRepository $classRepository;
    /**
     * @inject
     */
    private IClassSelectionRequestRepository $selectionRequestRepository;
    /**
     * @inject
     */
    private IProfileIconRepository $profileIconRepository;
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
                "Zadal/a jsi chybnou přezdívku (nebo email) a/nebo heslo",
                self::NEGATIVE_MESSAGE
            );

            return false;
        }

        if ($user->isConfirmed() === false) {
            $this->messageManager->addMessage(
                "Tvůj účet zatím nebyl potvrzen. Podívej se do emailové schránky, jistě ti přišel potvrzovací email.",
                self::NEGATIVE_MESSAGE
            );

            return false;
        }

        $this->logInUserToSystem($user, true);

        return true;
    }

    /**
     * Changes user's password
     *
     * @param string $oldPassword
     * @param string $password
     * @param string $passwordAgain
     *
     * @return bool Has it been successful?
     */
    public function changePassword(string $oldPassword, string $password, string $passwordAgain): bool
    {
        if(empty($oldPassword) || empty($password) || empty($passwordAgain)) {
            $this->messageManager->addMessage("Nebyla vyplněna všechna pole", self::NEGATIVE_MESSAGE);

            return false;
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->getUser();
        if (!password_verify($oldPassword, $user->getPassword())) {
            $this->messageManager->addMessage("Zadané aktuální heslo není správné.", self::NEGATIVE_MESSAGE);

            return false;
        }

        if ($password !== $passwordAgain) {
            $this->messageManager->addMessage("Zadaná nová hesla nejsou shodná", self::NEGATIVE_MESSAGE);

            return false;
        }

        if($oldPassword === $password) {
            $this->messageManager->addMessage("Nové heslo nesmí být stejné jako současné", self::NEGATIVE_MESSAGE);

            return false;
        }

        if (mb_strlen($password) < 8) {
            $this->messageManager->addMessage("Heslo musí být dlouhé minimálně 8 znaků", self::NEGATIVE_MESSAGE);

            return false;
        }

        if ((bool)preg_match("%[a-z]+%", $password) === false || (bool)preg_match("%[A-Z]+%", $password) === false
            || (bool)preg_match("%\d+%", $password) === false) {
            $this->messageManager->addMessage(
                "Heslo musí obsahovat alespoň jedno malé písmeno, velké písmeno a číslici",
                self::NEGATIVE_MESSAGE
            );

            return false;
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $this->userRepository->changePassword((int)$user->getId(), $password);

        $this->messageManager->addMessage("Tvoje heslo bylo úspěšně změněno", self::POSITIVE_MESSAGE);

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
            $this->messageManager->addMessage("Zkontroluj si správnost jména a příjmení", self::NEGATIVE_MESSAGE);

            return false;
        } else {
            $firstName = ucfirst($firstName);

            $lastNameParts = explode(" ", $lastName);
            $lastNameParts = array_map(fn($item) => ucfirst($item), $lastNameParts);
            $lastName = implode(" ", $lastNameParts);
        }

        // Nickname
        if (mb_strlen($nickname) < 3) {
            $this->messageManager->addMessage("Přezdívka by měla být dlouhá alespoň 3 znaky", self::NEGATIVE_MESSAGE);
        }

        // Email
        if (filter_input(FILTER_VALIDATE_EMAIL, $email) === false) {
            $this->messageManager->addMessage("Zkontroluj si správnost emailové adresy", self::NEGATIVE_MESSAGE);

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
                "Heslo musí obsahovat alespoň jedno malé písmeno, velké písmeno a číslici",
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
            $this->messageManager->addMessage("Zadaný email je již používán", self::NEGATIVE_MESSAGE);

            return false;
        }

        $rank = $this->rankRepository->getDefaultForUsers();

        $this->userRepository->add($nickname, $password, $rank, $firstName, $lastName, $email);

        // Confirmation token
        $token = $this->generateTokenForRegistration($this->userRepository->getByUsernameOrEmail($nickname));

        // Confirmation email
        $baseHref = rtrim($this->server->getBaseHref(), "/");
        $link = "{$baseHref}{$this->server->getUrl()}/confirm/{$token}";

        $message = $this->printer->getFileHTML(__DIR__."/../views/emails/email-confirm.latte", ['link' => $link]);

        $this->mailer->sendMail(
            "Moje Studium",
            "moje-studium@ceskydj.cz",
            [['address' => $email, 'name' => "{$firstName} {$lastName}"]],
            "Potvrzení registrace",
            $message
        );

        $this->messageManager->addMessage("Děkujeme za tvoji registraci. Poslali jsme ti potvrzovací email, tak mrkni do schránky ;)", self::POSITIVE_MESSAGE);

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
            $this->messageManager->addMessage("Tvůj účet již byl potvrzen nebo máš chybný klíč", self::NEGATIVE_MESSAGE);

            return false;
        }

        $this->tokenRepository->deactivate($token->getId());
        $this->userRepository->confirm((int)$token->getUser()->getId());

        $this->messageManager->addMessage("Tvůj účet byl úspěšně potvrzen. Nyní se již můžeš přihlásit", self::POSITIVE_MESSAGE);

        return true;
    }

    /**
     * Checks user's first login state
     *
     * @return bool Is current user's login first?
     */
    public function isFirstLogin(): bool
    {
        /**
         * @var $user User
         */
        $user = $this->getUser();

        return $user->isFirstLogin();
    }

    /**
     * Set first login as fully completed for current user
     */
    public function setFirstLoginCompleted(): void
    {
        $this->userRepository->completeFirstLogin((int)$this->user->getId());
    }

    /**
     * Applies user's choice to select the school class
     *
     * @param int $schoolId School ID
     * @param int $classId Class ID
     *
     * @return bool Has it been successful?
     */
    public function selectSchoolClass(int $schoolId, int $classId): bool {
        /**
         * @var $user \App\Entity\User
         */
        $user = $this->user;
        if ($user->getClass() !== null) {
            $this->messageManager->addMessage("Už jsi v nějaké třídě, pokud chceš jinam, nejprve odejdi z té stávající", self::NEGATIVE_MESSAGE);

            return false;
        }

        if (empty($schoolId) || empty($classId)) {
            $this->messageManager->addMessage("Nebyla vyplněna všechna pole", self::NEGATIVE_MESSAGE);

            return false;
        }

        if (($school = $this->schoolRepository->getById($schoolId)) === null) {
            $this->messageManager->addMessage("Zvolená škola neexistuje", self::NEGATIVE_MESSAGE);

            return false;
        }

        if (($class = $this->classRepository->getById($classId)) === null) {
            $this->messageManager->addMessage("Zvolená třída neexistuje", self::NEGATIVE_MESSAGE);

            return false;
        }

        if ($class->getSchool()->getId() !== $school->getId()) {
            $this->messageManager->addMessage("Zvolená třída není součástí zvolené školy", self::NEGATIVE_MESSAGE);

            return false;
        }

        $this->selectionRequestRepository->add($user, $class);
        $this->messageManager->addMessage("Tvoje žádost o přijetí do třídy byla odeslána. Vyčkej prosím, až ji někdo potvrdí", self::POSITIVE_MESSAGE);

        return true;
    }

    /**
     * Returns all users in the system with their quota using
     *
     * @return array All users in the system
     */
    public function getAllUsersInSystem(): array
    {
        $result = [];
        $users = $this->userRepository->getAll();
        foreach ($users as $user) {
            $folderSize = $this->getDirectorySize($this->getUserFolder($user));

            $result[] = [
                'user' => $user,
                'quota' => [
                    'limit' => (FileManager::USER_QUOTA / 1024 / 1024)."&nbsp;MB",
                    'absolute' => round($folderSize / 1024 / 1024, 1),
                    'relative' => round($folderSize / FileManager::USER_QUOTA * 100)
                ],
            ];
        }

        return $result;
    }

    /**
     * Changes user's rank
     *
     * @param int $userId User's ID
     * @param string $newRank New rank's name ("user" || "admin")
     *
     * @return string JSON response
     */
    public function changeRank(int $userId, string $newRank): string
    {
        if (empty($userId) || empty($newRank)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        /**
         * @var \App\Entity\User $user
         */
        if (($user = $this->userRepository->getById($userId)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Uživatel neexistuje",
                ]
            );
        }

        if (($rank = $this->rankRepository->getByName($newRank)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Hodnost neexistuje",
                ]
            );
        }

        $this->userRepository->changeRank((int)$user->getId(), $rank);

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Deletes existing user
     *
     * @param int $userId User's ID
     *
     * @return string JSON response
     */
    public function deleteUser(int $userId): string
    {
        if (empty($userId)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        /**
         * @var \App\Entity\User $user
         */
        if (($user = $this->userRepository->getById($userId)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Uživatel neexistuje",
                ]
            );
        }

        $this->userRepository->delete($userId);

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Returns path to user's folder
     *
     * @param \App\Entity\User $user User
     *
     * @return string|null Absolute path to user's folder or null if doesn't exist
     * @todo Move to FileManager (after solving the issue with DI)
     */
    public function getUserFolder(User $user): ?string
    {
        if (($path = realpath(__DIR__."/../../".FileManager::DATA_DIR."/{$user->getId()}"))) {
            return $path;
        } else {
            return null;
        }
    }

    /**
     * Returns size of the directory (and its content)
     * @todo Move to FileManager (after solving the issue with DI)
     *
     * @param string|null $name Directory name (full path)
     *
     * @return int Size (in bytes)
     */
    public function getDirectorySize(?string $name): int
    {
        if ($name === null) {
            return 0;
        }

        $size = 0;
        $directoryIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($name, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($directoryIterator as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    /**
     * Returns user's file quota usage
     *
     * @param \App\Entity\User $user User
     *
     * @return array Quota usage indicators
     */
    public function getUserQuota(User $user): array
    {
        $folderSize = $this->getDirectorySize($this->getUserFolder($user));

        return [
            'limit'    => (FileManager::USER_QUOTA / 1024 / 1024)."&nbsp;MB",
            'absolute' => round($folderSize / 1024 / 1024, 1),
            'relative' => round($folderSize / FileManager::USER_QUOTA * 100),
        ];
    }

    /**
     * Changes user's profile image icon
     *
     * @param int $icon New icon's ID
     *
     * @return string JSON response
     */
    public function changeProfileImageIcon(int $icon): string
    {
        if (empty($icon)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($icon = $this->profileIconRepository->getById($icon)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Ikona neexistuje",
                ]
            );
        }

        $this->userRepository->changeProfileImageIcon((int)$this->getUser()->getId(), $icon);

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Changes user's profile image colors
     *
     * @param string $iconColor Icon color in HEX
     * @param string $backgroundColor Background color in HEX
     *
     * @return string JSON response
     */
    public function changeProfileImageColors(string $iconColor, string $backgroundColor): string
    {
        if (empty($iconColor) || empty($backgroundColor)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        $iconColor = strtoupper($iconColor);
        if (preg_match("%^#[0-9A-F]{6}$%", $iconColor) === 0) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Chybný formát barvy ikony. Správně je to: #ABCDEF",
                ]
            );
        }

        $backgroundColor = strtoupper($backgroundColor);
        if (preg_match("%^#[0-9A-F]{6}$%", $backgroundColor) === 0) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Chybný formát barvy pozadí. Správně je to: #ABCDEF",
                ]
            );
        }

        if ($iconColor === $backgroundColor) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Barva ikony musí být odlišná od barvy pozadí",
                ]
            );
        }

        $this->userRepository->changeProfileImageColors((int)$this->getUser()->getId(), $iconColor, $backgroundColor);

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Changes user's personal data
     *
     * @param string $firstName First name
     * @param string $lastName Last name
     * @param string $nickname Login name (nick)
     *
     * @return string JSON response
     */
    public function changeUserData(string $firstName, string $lastName, string $nickname): string
    {
        if (empty($firstName) || empty($lastName) || empty($nickname)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (mb_strlen($firstName) < 3 || mb_strlen($lastName) < 3) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Zkontroluj si správnost jména a příjmení",
                ]
            );
        }

        $firstName = ucfirst($firstName);

        $lastNameParts = explode(" ", $lastName);
        $lastNameParts = array_map(fn($item) => ucfirst($item), $lastNameParts);
        $lastName = implode(" ", $lastNameParts);

        if (mb_strlen($nickname) < 3) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Přezdívka by měla být dlouhá alespoň 3 znaky",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->getUser();
        if ($user->getData()->getUsername() !== $nickname && $this->userRepository->getByUsernameOrEmail($nickname) !== null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Zadaná přezdívka je již používána",
                ]
            );
        }

        $this->userRepository->edit((int)$user->getId(), $nickname, $firstName, $lastName);

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Removes logged-in user from his class
     *
     * @return string JSON response
     */
    public function leaveClass(): string
    {
        /**
         * @var $user \App\Entity\User
         */
        $user = $this->getUser();
        $class = $user->getClass();

        $this->userRepository->selectClass((int)$user->getId(), null);

        if ($class->getUsers()->isEmpty()) {
            $this->classRepository->delete($class->getId());
        }

        return json_encode(
            [
                'success' => true,
            ]
        );
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
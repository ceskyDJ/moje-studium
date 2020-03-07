<?php

declare(strict_types = 1);

namespace App\Model;

use App\Repository\Abstraction\IReminderRepository;
use DateTime;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Mammoth\DI\DIClass;
use function json_encode;

/**
 * Manager for reminders
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Model
 */
class ReminderManager
{
    use DIClass;

    /**
     * @inject
     */
    private IReminderRepository $reminderRepository;
    /**
     * @inject
     */
    private UserManager $userManager;

    /**
     * Takes up reminder for user
     *
     * @param int $id Shared reminder's ID
     *
     * @return bool Has it been successful?
     */
    public function takeUp(int $id): bool
    {
        /**
         * @var $user \App\Entity\User
         */
        if (!($user = $this->userManager->getUser())->isLoggedIn()) {
            return false;
        }

        if (($reminder = $this->reminderRepository->getSharedById($id)) === null) {
            return false;
        }

        if (($targetUser = $reminder->getTargetUser()) !== null) {
            if ($targetUser->getId() !== $user->getId()) {
                return false;
            }
        }

        if (($targetClass = $reminder->getTargetClass()) !== null) {
            if ($targetClass->getId() !== $user->getClass()->getId()) {
                return false;
            }
        }

        try {
            $this->reminderRepository->takeUp($user, $id);
        } catch (UniqueConstraintViolationException $e) {
            // Not unique -> user has already taken up this item
            return false;
        }

        return true;
    }

    /**
     * Return user's reminders in JSON
     *
     * @param int $year From what year?
     * @param int $week From what week?
     *
     * @return string Reminders in JSON format
     */
    public function getPrivateRemindersForAjax(int $year, int $week): string
    {
        try {
            $from = (new DateTime())->setISODate($year, $week);
            $to = (new DateTime())->setISODate($year, $week, 6); // 6 days after monday
        } catch (Exception $e) {
            $from = null;
            $to = null;
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        if (!$user->isLoggedIn()) {
            return "";
        }

        $reminders = $this->reminderRepository->getByUser($user, $from, $to);

        $output = [];
        /**
         * @var $reminder \App\Entity\PrivateReminder
         */
        foreach ($reminders as $reminder) {
            $output[] = [
                'when' => $reminder->getFormattedWhen(),
                'subject' => $reminder->getSubject()->getShortcut(),
                'type' => $reminder->getType(),
                'content' => $reminder->getContent()
            ];
        }

        return json_encode($output);
    }
}
<?php

declare(strict_types = 1);

namespace App\Model;

use App\Repository\Abstraction\IReminderRepository;
use App\Repository\Abstraction\ISchoolSubjectRepository;
use App\Utils\DateHelper;
use DateTime;
use Doctrine\Common\Util\Debug;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Mammoth\DI\DIClass;
use Tracy\Debugger;
use function array_keys;
use function bdump;
use function count;
use function explode;
use function json_encode;
use function mb_substr;
use function str_replace;

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
    private ISchoolSubjectRepository $subjectRepository;
    /**
     * @inject
     */
    private UserManager $userManager;
    /**
     * @inject
     */
    private DateHelper $dateHelper;

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
            $to = (new DateTime())->setISODate($year, $week, 7);
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
                'when'    => $reminder->getFormattedWhen(),
                'subject' => $reminder->getSubject()->getShortcut(),
                'type'    => $reminder->getType(),
                'content' => $reminder->getContent(),
            ];
        }

        return json_encode($output);
    }

    /**
     * Returns private reminders divided into days
     *
     * @param int|null $year From what year?
     * @param int|null $week From what week?
     *
     * @return array Array with days in week (every day is an array with PrivateReminder instances)
     * @noinspection PhpDocMissingThrowsInspection Can't be thrown
     */
    public function getPrivateRemindersDividedIntoDays(?int $year = null, ?int $week = null): array
    {
        if ($year !== null && $week !== null) {
            $from = (new DateTime())->setISODate($year, $week);
            $to = (new DateTime())->setISODate($year, $week, 7);
            $currentDay = clone $from;
        } else {
            $from = null;
            $to = null;
            $currentDay = $this->dateHelper->getMondayDate(new DateTime());
        }

        $result = [];
        for ($i = 0; $i < 7; $i++) {
            $result[(int)$currentDay->format("j")] = [
                'date'      => $this->dateHelper->getCzechShortDate($currentDay),
                'short-date' => $currentDay->format("j.n."),
                'reminders' => [],
            ];

            $currentDay->modify("+ 1 days");
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        $reminders = $this->reminderRepository->getByUser($user, $from, $to);

        foreach ($reminders as $reminder) {
            $dateDay = (int)$reminder->getWhen()->format("j");

            $result[$dateDay]['reminders'][] = $reminder;
        }

        return $result;
    }

    /**
     * Returns number of use days
     *
     * @param array $remindersDividedIntoDays Array of reminders divided to individual days
     *
     * @return int Number of days with reminders (+ previous days from monday - included)
     */
    public function getNumberOfUseDays(array $remindersDividedIntoDays): int
    {
        // Minimum is 5 days (monday to friday)
        $numberOfUseDays = 5;

        // Complete with other days
        $keys = array_keys($remindersDividedIntoDays);
        for ($i = $numberOfUseDays - 1; $i < count($remindersDividedIntoDays) - 1; $i++) {
            // If the day has at least one reminder, change number of use days to its position (starts from 1)
            if (!empty($remindersDividedIntoDays[$keys[$i]])) {
                $numberOfUseDays = $i + 1;
            }
        }

        return $numberOfUseDays;
    }

    /**
     * Returns user's reminders divided into days for AJAX
     *
     * @param int $year From what year?
     * @param int $week From what week?
     *
     * @return string Reminders divided into day in JSON format
     */
    public function getPrivateRemindersInDaysForAjax(int $year, int $week): string
    {
        $remindersInDays = $this->getPrivateRemindersDividedIntoDays($year, $week);

        $result = [];
        $i = 0;
        foreach ($remindersInDays as $day) {
            $result['days'][$i]['date'] = $day['date'];
            $result['days'][$i]['shortDate'] = $day['short-date'];

            /**
             * @var $reminder \App\Entity\PrivateReminder
             */
            foreach ($day['reminders'] as $reminder) {
                $result['days'][$i]['reminders'][] = [
                    'id'      => $reminder->getId(),
                    'subject' => $reminder->getSubject()->getShortcut(),
                    'type'    => $reminder->getType(),
                    'content' => $reminder->getContent(),
                ];
            }

            $i++;
        }

        $result['useDays'] = $this->getNumberOfUseDays($remindersInDays);

        return json_encode($result);
    }

    /**
     * Adds new reminder
     *
     * @param string $date New date - When does it happens?
     * @param int $year Year part of date
     * @param string $subject New subject
     * @param string $type New type (school-event, test, homework)
     * @param string $content New content
     *
     * @return string JSON response
     */
    public function addReminder(string $date, int $year, int $subject, string $type, string $content): string
    {
        if (empty($date) || empty($subject) || empty($type) || empty($content)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        if (!($user = $this->userManager->getUser())->isLoggedIn()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Není přihlášen žádný uživatel",
                ]
            );
        }

        if (($subject = $this->subjectRepository->getById($subject)) === null || $subject->getClass()->getId() !== $user->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Zadaný předmět v tvé třídě není",
                ]
            );
        }

        try {
            // For ex.: $date = "Po 16. 3."
            $date = str_replace(" ", "", mb_substr($date, 3));
            [$day, $month] = explode(".", $date);
            $when = (new DateTime())->setDate($year, (int)$month, (int)$day);
        } catch (Exception $e) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Zadaný datum je chybný",
                ]
            );
        }

        $reminder = $this->reminderRepository->add($user, $type, $content, $when, $subject);

        return json_encode(
            [
                'success' => true,
                'id' => $reminder->getId()
            ]
        );
    }

    /**
     * Edits existing reminder
     *
     * @param int $id ID
     * @param string $date When does it happens?
     * @param int $year Year part of date
     * @param string $subject Subject
     * @param string $type Type (school-event, test, homework)
     * @param string $content Content
     *
     * @return string JSON response
     */
    public function editReminder(int $id, string $date, int $year, int $subject, string $type, string $content): string
    {
        if (empty($id) || empty($date) || empty($subject) || empty($type) || empty($content)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        /**
         * @var $owner \App\Entity\User
         */
        if (!($owner = $this->userManager->getUser())->isLoggedIn()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Není přihlášen žádný uživatel",
                ]
            );
        }

        if (($subject = $this->subjectRepository->getById($subject)) === null || $subject->getClass()->getId() !== $owner->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Zadaný předmět v tvé třídě není",
                ]
            );
        }

        if (($reminder = $this->reminderRepository->getById($id)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Poznámka s daným ID neexistuje",
                ]
            );
        }

        if ($reminder->getOwner()->getId() !== $owner->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Poznámka, kterou chceš upravit, není tvoje",
                ]
            );
        }

        try {
            $date = str_replace(" ", "", $date);
            [$day, $month] = explode(".", $date);
            $when = (new DateTime())->setDate($year, $month, $day);
        } catch (Exception $e) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Zadaný datum je chybný",
                ]
            );
        }

        $this->reminderRepository->edit($id, $owner, $type, $content, $when, $subject);

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Deletes user's reminder
     *
     * @param int $id Reminder's ID
     *
     * @return bool Has is been successful?
     */
    public function deleteReminder(int $id): bool
    {
        if (empty($id)) {
            return false;
        }

        if (($reminder = $this->reminderRepository->getById($id)) === null) {
            return false;
        }

        if (!($user = $this->userManager->getUser())->isLoggedIn()) {
            return false;
        }

        if ($reminder->getOwner()->getId() !== $user->getId()) {
            return false;
        }

        $this->reminderRepository->delete($id);

        return true;
    }
}
<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use App\Model\NoteManager;
use App\Model\ReminderManager;
use App\Model\UserManager;
use App\Repository\Abstraction\INoteRepository;
use App\Repository\Abstraction\IReminderRepository;
use App\Repository\Abstraction\ISchoolSubjectRepository;
use App\Repository\Abstraction\IUserRepository;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;

/**
 * Controller for reminders and notes
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Application
 */
class RemindersAndNotesController extends Controller
{
    use DIClass;

    /**
     * @inject
     */
    private ResponseFactory $responseFactory;
    /**
     * @inject
     */
    private IReminderRepository $reminderRepository;
    /**
     * @inject
     */
    private INoteRepository $noteRepository;
    /**
     * @inject
     */
    private ISchoolSubjectRepository $subjectRepository;
    /**
     * @inject
     */
    private IUserRepository $userRepository;
    /**
     * @inject
     */
    private UserManager $userManager;
    /**
     * @inject
     */
    private ReminderManager $reminderManager;
    /**
     * @inject
     */
    private NoteManager $noteManager;

    /**
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        return $this->privateAction($request);
    }

    /**
     * Private (logged-in user's) reminders and notes
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function privateAction(Request $request): Response
    {
        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();

        $title = "Moje poznámky";
        if ($user->getClass() !== null) {
            $title = "Moje upozornění a poznámky";
        }

        $response = $this->responseFactory->create($request)->setContentView("private-reminders-and-notes")->setTitle(
            $title
        );

        $response->setDataVar(
            "reminderDays",
            $reminders = $this->reminderManager->getPrivateRemindersDividedIntoDays()
        );
        $response->setDataVar("reminderUseDays", $this->reminderManager->getNumberOfUseDays($reminders));
        $response->setDataVar("notes", $this->noteRepository->getByUser($user));
        $response->setDataVar("subjects", $this->subjectRepository->getByUser($user));

        if ($user->getClass() !== null) {
            $response->setDataVar("usersInClass", $this->userRepository->getByClass($user->getClass()));
        }

        return $response;
    }

    /**
     * Shared reminders and notes (with logged-in user or his class)
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function sharedAction(Request $request): Response
    {
        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();

        $title = "Sdílené poznámky";
        if ($user->getClass() !== null) {
            $title = "Sdílená upozornění a poznámky";
        }

        $response = $this->responseFactory->create($request)->setContentView("shared-reminders-and-notes")->setTitle(
            $title
        );

        $response->setDataVar("sharedReminders", $this->reminderRepository->getSharedByUserOrItsClassWithLimit($user));
        $response->setDataVar("sharedNotes", $this->noteRepository->getSharedByUserOrItsClassWithLimit($user));

        return $response;
    }

    /**
     * Register user's take up of reminder or note
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function takeUpAjaxAction(Request $request): Response
    {
        $data = $request->getParsedUrl()->getData();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        if (!isset($data[0]) || empty($type = $data[0])) {
            return $response;
        }

        if ($type === "reminder") {
            $this->reminderManager->takeUp((int)$data[1]);
        } elseif ($type === "note") {
            $this->noteManager->takeUp((int)$data[1]);
        }

        return $response;
    }

    /**
     * Get reminders for front-end
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function getRemindersAjaxAction(Request $request): Response
    {
        $data = $request->getParsedUrl()->getData();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->reminderManager->getPrivateRemindersForAjax((int)$data[0], (int)$data[1]));

        return $response;
    }

    /**
     * Get reminders divided into days for front-end
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function getRemindersInDaysAjaxAction(Request $request): Response
    {
        $data = $request->getParsedUrl()->getData();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->reminderManager->getPrivateRemindersInDaysForAjax((int)$data[0], (int)$data[1])
        );

        return $response;
    }

    /**
     * Add new reminder to system
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function addReminderAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->reminderManager->addReminder(
                $data['date'],
                (int)$data['year'],
                (int)$data['subject'],
                $data['type'],
                $data['content']
            )
        );

        return $response;
    }

    /**
     * Edit existing reminder
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function editReminderAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->reminderManager->editReminder(
                (int)$data['id'],
                $data['date'],
                (int)$data['year'],
                (int)$data['subject'],
                $data['type'],
                $data['content']
            )
        );

        return $response;
    }

    /**
     * Delete existing reminder
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteReminderAjaxAction(Request $request): Response
    {
        $data = $request->getParsedUrl()->getData();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $this->reminderManager->deleteReminder((int)$data[0]);

        return $response;
    }

    /**
     * Share reminder
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function shareReminderAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->reminderManager->shareReminder((int)$data['reminder'], $data['with'], (int)$data['schoolmate'])
        );

        return $response;
    }

    /**
     * Add new note to system
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function addNoteAjaxAction(Request $request): Response
    {
        $post = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->noteManager->addNote($post['content']));

        return $response;
    }

    /**
     * Edit existing note
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function editNoteAjaxAction(Request $request): Response
    {
        $post = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->noteManager->editNote((int)$post['id'], $post['content']));

        return $response;
    }

    /**
     * Delete existing note
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteNoteAjaxAction(Request $request): Response
    {
        $data = $request->getParsedUrl()->getData();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $this->noteManager->deleteNote((int)$data[0]);

        return $response;
    }

    /**
     * Share note
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function shareNoteAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->noteManager->shareNote((int)$data['note'], $data['with'], (int)$data['schoolmate'])
        );

        return $response;
    }
}
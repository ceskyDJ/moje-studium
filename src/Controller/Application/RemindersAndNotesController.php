<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use App\Model\NoteManager;
use App\Model\ReminderManager;
use App\Model\UserManager;
use App\Repository\Abstraction\INoteRepository;
use App\Repository\Abstraction\IReminderRepository;
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
    private UserManager $userManager;
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
        return $this->responseFactory->create($request)->setContentView("private-reminders-and-notes")->setTitle(
            "Moje upozornění a soubory"
        );
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
        return $this->responseFactory->create($request)->setContentView("shared-reminders-and-notes")->setTitle(
            "Sdílené upozornění a poznámky"
        );
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
    public function getRemindersAjaxAction(Request $request)
    {
        $data = $request->getParsedUrl()->getData();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->reminderManager->getPrivateRemindersForAjax((int)$data[0], (int)$data[1]));

        return $response;
    }
}
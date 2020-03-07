<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use App\Model\NotificationManager;
use App\Model\UserManager;
use App\Repository\Abstraction\IFileRepository;
use App\Repository\Abstraction\INoteRepository;
use App\Repository\Abstraction\IReminderRepository;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;
use Mammoth\Url\Abstraction\IRouter;

/**
 * Controller for application's home - summary page
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Application
 */
class HomeController extends Controller
{
    use DIClass;

    /**
     * @inject
     */
    private ResponseFactory $responseFactory;
    /**
     * @inject
     */
    private NotificationManager $notificationManager;
    /**
     * @inject
     */
    private UserManager $userManager;
    /**
     * @inject
     */
    private IRouter $router;
    /**
     * @inject
     */
    private IFileRepository $fileRepository;
    /**
     * @inject
     */
    private IReminderRepository $reminderRepository;
    /**
     * @inject
     */
    private INoteRepository $noteRepository;

    /**
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        if ($this->userManager->isFirstLogin() === true) {
            $this->router->route($request->getParsedUrl()->setController("class")->setAction("select")->setData(null));

            exit;
        }

        $response = $this->responseFactory->create($request)->setContentView("summary")->setTitle("Přehled");

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        $response->setDataVar("privateReminders", $this->reminderRepository->getByUser($user));
        $response->setDataVar("privateNotes", $this->noteRepository->getByUser($user));
        $response->setDataVar("sharedFiles", $this->fileRepository->getSharedByUserOrItsClassWithLimit($user, 5));
        $response->setDataVar(
            "sharedReminders",
            $this->reminderRepository->getSharedByUserOrItsClassWithLimit($user, 5)
        );
        $response->setDataVar("sharedNotes", $this->noteRepository->getSharedByUserOrItsClassWithLimit($user, 5));

        return $response;
    }

    /**
     * Clear read notifications
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function clearNotificationsAjaxAction(Request $request): Response
    {
        $this->notificationManager->clearNotificationsForLoggedInUser();

        return $this->responseFactory->create($request)->setContentView("#code");
    }
}
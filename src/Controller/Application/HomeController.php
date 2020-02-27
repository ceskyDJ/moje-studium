<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use App\Model\NotificationManager;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;

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
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        return $this->responseFactory->create($request)->setContentView("summary")->setTitle("Přehled");
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
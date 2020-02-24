<?php

declare(strict_types = 1);

namespace App\Controller\Application;

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
        return $this->responseFactory->create($request)->setContentView("private-reminders-and-notes")
            ->setTitle("Moje upozornění a soubory");
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
        return  $this->responseFactory->create($request)->setContentView("shared-reminders-and-notes")
            ->setTitle("Sdílené upozornění a poznámky");
    }
}
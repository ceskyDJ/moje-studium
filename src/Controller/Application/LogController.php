<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;

/**
 * Controller for log-* operations (log-in, log-out)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Application
 */
class LogController extends Controller
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
        return $this->inAction($request);
    }

    /**
     * Log-in visitor
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function inAction(Request $request): Response
    {
        return $this->responseFactory->create($request)->setContentView("log-in")
            ->setTitle("Přihlášení uživatele");
    }

    /**
     * Log-out user
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function outAction(Request $request): Response
    {
        return $this->responseFactory->create($request);
    }
}
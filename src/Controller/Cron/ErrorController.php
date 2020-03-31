<?php

declare(strict_types = 1);

namespace App\Controller\Cron;

use Mammoth\Config\Configurator;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;
use Tracy\Debugger;
use function file_get_contents;

/**
 * Controller for errors
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Cron
 */
class ErrorController extends Controller
{
    use DIClass;

    /**
     * Layout path (error controllers are almost the same but each of them required different layout)
     */
    private const LAYOUT_PATH = "Cron/#layout";
    /**
     * Path to views folder (for loading JSON files to templates)
     */
    private const VIEWS_PATH = __DIR__."/../../views/Cron";

    /**
     * @inject
     */
    private ResponseFactory $responseFactory;
    /**
     * @inject
     */
    private Configurator $configurator;

    /**
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        $error = ($request->getParsedUrl()->getData()[0] ?? null);

        // Aliases of actions controlled by this controller
        switch ($error) {
            case 404:
                return $this->notFoundAction($request);
            case 403:
                return $this->accessDeniedAction($request);
            default:
                return $this->systemErrorAction($request);
        }
    }

    /**
     * @see Controller::defaultAction()
     * @noinspection PhpDocSignatureInspection
     */
    public function notFoundAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request);

        Debugger::$showBar = false;

        return $response->setContentView("#code")
            ->setLayoutView(self::LAYOUT_PATH)
            ->setDataVar("data", file_get_contents(self::VIEWS_PATH."/error-404.json"));
    }

    /**
     * @see Controller::defaultAction()
     * @noinspection PhpDocSignatureInspection
     */
    public function accessDeniedAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request);

        Debugger::$showBar = false;

        return $response->setContentView("#code")
            ->setLayoutView(self::LAYOUT_PATH)
            ->setDataVar("data", file_get_contents(self::VIEWS_PATH."/error-403.json"));
    }

    /**
     * @see Controller::defaultAction()
     * @noinspection PhpDocSignatureInspection
     */
    public function systemErrorAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request);

        Debugger::$showBar = false;

        return $response->setContentView("#code")
            ->setLayoutView(self::LAYOUT_PATH)
            ->setDataVar("data", file_get_contents(self::VIEWS_PATH."/error-500.json"));
    }
}
<?php

declare(strict_types = 1);

namespace App\Controller\Presentation;

use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;

/**
 * Controller for error pages
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Presentation
 */
class ErrorController extends Controller
{
    use DIClass;

    /**
     * Layout path (error controllers are almost the same but each of them required different layout)
     */
    private const LAYOUT_PATH = "Application/#layout";

    /**
     * @inject
     */
    private ResponseFactory $responseFactory;

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

        return $response->setContentView("../error-404")
            ->setLayoutView(self::LAYOUT_PATH)
            ->setTitle("Stránka nenalezena");
    }

    /**
     * @see Controller::defaultAction()
     * @noinspection PhpDocSignatureInspection
     */
    public function accessDeniedAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request);

        return $response->setContentView("../error-403")
            ->setLayoutView(self::LAYOUT_PATH)
            ->setTitle("Přístup zamítnut");
    }

    /**
     * @see Controller::defaultAction()
     * @noinspection PhpDocSignatureInspection
     */
    public function systemErrorAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request);

        return $response->setContentView("../error-500")
            ->setLayoutView(self::LAYOUT_PATH)
            ->setTitle("Systémová chyba");
    }
}
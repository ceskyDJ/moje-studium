<?php

declare(strict_types = 1);

namespace App\Controller\Presentation;

use App\Model\UserManager;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;
use Mammoth\Url\Abstraction\IRouter;
use Mammoth\Url\Abstraction\IUrlManager;
use function bdump;

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
    private IUrlManager $urlManager;

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
        if ($_POST) {
            if ($this->userManager->logIn($_POST['username'], $_POST['password']) === true) {
                $summaryPage = $request->getParsedUrl()->setComponent("application")->setController(null)->setAction(
                        null
                    )->setData(null);

                $this->router->route($summaryPage);
            }
        }

        return $this->responseFactory->create($request)->setContentView("log-in")->setTitle("Přihlášení uživatele");
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
        $this->userManager->logOutUserFromSystem();

        $presentation = $request->getParsedUrl()->setComponent(null)->setController(null)->setAction(null)->setData(
            null);

        $this->router->route($presentation);

        exit;
    }
}
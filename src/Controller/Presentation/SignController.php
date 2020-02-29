<?php

declare(strict_types = 1);

namespace App\Controller\Presentation;

use App\Model\UserManager;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;
use Mammoth\Mailing\Abstraction\IMailer;
use Mammoth\Url\Abstraction\IRouter;
use Mammoth\Url\Abstraction\IUrlManager;
use function bdump;

/**
 * Controller for sign-* operations (sign-up, sign-in, sign-out)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Application
 */
class SignController extends Controller
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
     * Register (sign up) new user
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function upAction(Request $request): Response
    {
        $post = $request->getPost();

        if ($post) {
            $this->userManager->register(
                $post['first-name'],
                $post['last-name'],
                $post['nickname'],
                $post['email'],
                $post['password'],
                $post['password-again']
            );
        }

        $data = $request->getParsedUrl()->getData();
        if (isset($data[0]) && $data[0] === "confirm") {
            $this->userManager->confirmUser($data[1]);
        }

        return $this->responseFactory->create($request)->setContentView("register")->setTitle("Registrace");
    }

    /**
     * Log-in (sign in) user
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function inAction(Request $request): Response
    {
        $post = $request->getPost();

        if ($post) {
            if ($this->userManager->logIn($post['username'], $post['password']) === true) {
                $summaryPage = $request->getParsedUrl()->setComponent("application")->setController(null)->setAction(
                    null
                )->setData(null);

                $this->router->route($summaryPage);

                exit;
            }
        }

        return $this->responseFactory->create($request)->setContentView("log-in")->setTitle("Přihlášení uživatele");
    }

    /**
     * Log-out (sign out) user
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
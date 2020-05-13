<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use App\Model\UserManager;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;

/**
 * Controller for change password page
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Application
 */
class ChangePasswordController extends Controller
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
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        $post = $_POST;

        if ($post) {
            $this->userManager->changePassword(
                $post['old-password'],
                $post['new-password'],
                $post['new-password-again']
            );
        }

        return $this->responseFactory->create($request)->setContentView("change-password")->setTitle("Změna hesla");
    }
}
<?php

declare(strict_types = 1);

namespace App\Controller;

use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;

/**
 * Controller for homepage
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\User
 */
class HomeController extends Controller
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
        $response = $this->responseFactory->create($request)->setContentView("home");

        return $response;
    }
}
<?php

declare(strict_types = 1);

namespace App\Controller\Admin;

use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;

/**
 * Controller for administration main page
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Admin
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
        return $this->responseFactory->create($request)->setContentView("main-page")->setTitle("Administrace");
    }
}
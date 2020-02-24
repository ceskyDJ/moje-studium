<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;

/**
 * Controller for school classes
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Application
 */
class ClassController extends Controller
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
        // TODO: redirect to profileAction(...) method
    }

    /**
     * Select class after registration
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function selectAction(Request $request): Response
    {
        return $this->responseFactory->create($request)->setContentView("select-class")
            ->setTitle("Výběr třídy");
    }

    /**
     * Create new class if doesn't exist
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function createAction(Request $request): Response
    {
        return  $this->responseFactory->create($request)->setContentView("create-class")
            ->setTitle("Vytvoření třídy");
    }
}
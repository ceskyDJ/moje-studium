<?php

declare(strict_types = 1);

namespace App\Controller\Cron;

use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Url\Abstraction\IRouter;

/**
 * Main controller for calling cron scripts
 * All common scripts are there
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Cron
 */
class HomeController extends Controller
{
    use DIClass;

    /**
     * @inject
     */
    private IRouter $router;

    /**
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        $parsedUrl = $request->getParsedUrl()->setController("error")->setAction("notFound")->setData(null);

        $this->router->route($parsedUrl);
        exit;
    }
}
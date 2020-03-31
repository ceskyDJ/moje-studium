<?php

declare(strict_types = 1);

namespace App\Controller\Cron;

use App\Repository\Abstraction\ISchoolClassRepository;
use Exception;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;
use Mammoth\Url\Abstraction\IRouter;
use function json_encode;

/**
 * Controller for cron scripts that works with classes
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Cron
 */
class ClassesController extends Controller
{
    use DIClass;

    /**
     * @inject
     */
    private ResponseFactory $responseFactory;
    /**
     * @inject
     */
    private IRouter $router;
    /**
     * @inject
     */
    private ISchoolClassRepository $classRepository;

    /**
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        $parsedUrl = $request->getParsedUrl()->setController("error")->setAction("notFound")->setData(null);

        $this->router->route($parsedUrl);
        exit;
    }

    /**
     * Update school classes' names
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function updateClassNamesAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request)->setContentView("#code");

        try {
            $response->setDataVar("data", json_encode(["success" => true]));
        } catch (Exception $e) {
            $response->setDataVar(
                "data",
                json_encode(
                    [
                        'success'       => false,
                        'error-code'    => ((int)$e->getCode()) !== null ? (int)$e->getCode() : 500,
                        'error-message' => $e->getMessage(),
                    ]
                )
            );
        }

        return $response;
    }
}
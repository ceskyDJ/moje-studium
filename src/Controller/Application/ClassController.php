<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use App\Model\ClassManager;
use App\Model\UserManager;
use App\Repository\Abstraction\ISchoolRepository;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;
use Mammoth\Url\Abstraction\IRouter;
use Tracy\Debugger;
use function is_numeric;

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
     * @inject
     */
    private IRouter $router;
    /**
     * @inject
     */
    private UserManager $userManager;
    /**
     * @inject
     */
    private ISchoolRepository $schoolRepository;
    /**
     * @inject
     */
    private ClassManager $classManager;

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
        $data = $request->getParsedUrl()->getData();
        if (isset($data[0]) && $data[0] === "delay") {
            $this->userManager->setFirstLoginCompleted();

            $this->router->route($request->getParsedUrl()->setController(null)->setAction(null)->setData(null));

            exit;
        }

        $post = $request->getPost();
        if ($post) {
            $this->userManager->selectSchoolClass((int)$post['school'], (int)$post['class']);
        }

        $response = $this->responseFactory->create($request)->setContentView("select-class")->setTitle("Výběr třídy");

        $response->setDataVar("schools", $this->schoolRepository->getAll());

        return $response;
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
        $post = $request->getPost();
        if ($post) {
            if ($this->classManager->addClass((int)$post['school'], $post['class-name'], $post['start-year'], $post['study-length'])) {
                $this->router->route($request->getParsedUrl()->setController(null)->setAction(null)->setData(null));

                exit;
            }
        }

        $response = $this->responseFactory->create($request)->setContentView("create-class")->setTitle("Vytvoření třídy");

        $response->setDataVar("schools", $this->schoolRepository->getAll());
        $response->setDataVar("startYears", $this->classManager->generateStartYears());

        return $response;
    }

    /**
     * Get classes by school for select class form
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function getClassesAjaxAction(Request $request): Response
    {
        $data = $request->getParsedUrl()->getData();

        $response = $this->responseFactory->create($request)->setContentView("#code");

        if (isset($data[0]) && is_numeric($data[0])) {
            $response->setDataVar("data", $this->classManager->getAllClassesInSchoolForForm($data[0]));
        }

        return $response;
    }
}
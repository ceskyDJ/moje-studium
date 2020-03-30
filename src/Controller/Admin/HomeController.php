<?php

declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Model\ClassManager;
use App\Model\FileManager;
use App\Model\UserManager;
use App\Repository\Abstraction\ISchoolClassRepository;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;
use function json_decode;

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
     * @inject
     */
    private UserManager $userManager;
    /**
     * @inject
     */
    private FileManager $fileManager;
    /**
     * @inject
     */
    private ClassManager $classManager;
    /**
     * @inject
     */
    private ISchoolClassRepository $classRepository;

    /**
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request)->setContentView("main-page")->setTitle("Administrace");

        $response->setDataVar("systemUsers", $this->userManager->getAllUsersInSystem());
        $response->setDataVar("classes", $this->classRepository->getAll());

        return $response;
    }

    /**
     * Change user's rank
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function changeRankAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->userManager->changeRank((int)$data['user'], $data['rank']));

        return $response;
    }

    /**
     * Delete all user's files
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteUserFilesAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->fileManager->deleteUsersFiles((int)$data['user']));

        return $response;
    }

    /**
     * Delete user
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteUserAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $responseAfterFilesDeleting = json_decode($this->fileManager->deleteUsersFiles((int)$data['user']), true);
        if ($responseAfterFilesDeleting['success'] === true) {
            $data = $this->userManager->deleteUser((int)$data['user']);
        } else {
            $data = $responseAfterFilesDeleting;
        }

        $response->setDataVar("data", $data);

        return $response;
    }

    /**
     * Delete class
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteClassAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->classManager->deleteClass((int)$data['class']));

        return $response;
    }
}
<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use App\Model\UserManager;
use App\Repository\Abstraction\IFileRepository;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;

/**
 * Controller for files
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Application
 */
class FilesController extends Controller
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
    private IFileRepository $fileRepository;

    /**
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        return $this->privateAction($request);
    }

    /**
     * Private (logged-in user's) files
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function privateAction(Request $request): Response
    {
        return $this->responseFactory->create($request)->setContentView("private-files")
            ->setTitle("Moje soubory");
    }

    /**
     * Shared files (from users or logged-in user'S class)
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function sharedAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request)->setContentView("shared-files")
            ->setTitle("Sdílené soubory");

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        $response->setDataVar("sharedFiles", $this->fileRepository->getSharedByUserOrItsClassWithLimit($user));

        return $response;
    }
}
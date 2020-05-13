<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use App\Model\UserManager;
use App\Repository\Abstraction\IClassGroupRepository;
use App\Repository\Abstraction\IClassSelectionRequestRepository;
use App\Repository\Abstraction\IProfileIconRepository;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;

/**
 * Controller for user profile and class profile
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Controller\Application
 */
class ProfilesController extends Controller
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
    private IProfileIconRepository $profileIconRepository;
    /**
     * @inject
     */
    private IClassSelectionRequestRepository $classSelectionRequestRepository;
    /**
     * @inject
     */
    private IClassGroupRepository $classGroupRepository;

    /**
     * @inheritDoc
     */
    public function defaultAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request)->setContentView("profiles")->setTitle(
            "Profil uživatele a třídy"
        );

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();

        $response->setDataVar("profileIcons", $this->profileIconRepository->getAll());
        $response->setDataVar("userQuota", $this->userManager->getUserQuota($user));
        $response->setDataVar(
            "classSelectionRequests",
            $this->classSelectionRequestRepository->getByClass($user->getClass(), true)
        );
        $response->setDataVar("classGroups", $this->classGroupRepository->getByClass($user->getClass()));

        return $response;
    }

    /**
     * Change user's profile image (icon, resp.)
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function changeProfileImageIconAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->userManager->changeProfileImageIcon((int)$data['icon']));

        return $response;
    }

    /**
     * Change user's profile image colors
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function changeProfileImageColorsAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->userManager->changeProfileImageColors($data['icon-color'], $data['background-color'])
        );

        return $response;
    }

    /**
     * Change user's personal data
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function changeUserDataAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->userManager->changeUserData($data['first-name'], $data['last-name'], $data['nickname'])
        );

        return $response;
    }
}
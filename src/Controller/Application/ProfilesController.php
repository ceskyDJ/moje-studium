<?php

declare(strict_types = 1);

namespace App\Controller\Application;

use App\Model\ClassManager;
use App\Model\UserManager;
use App\Repository\Abstraction\IClassGroupRepository;
use App\Repository\Abstraction\IClassSelectionRequestRepository;
use App\Repository\Abstraction\IProfileIconRepository;
use Mammoth\Controller\Common\Controller;
use Mammoth\DI\DIClass;
use Mammoth\Http\Entity\Request;
use Mammoth\Http\Entity\Response;
use Mammoth\Http\Factory\ResponseFactory;
use Tracy\Debugger;

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
    private ClassManager $classManager;
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

        // Data for users with class
        if ($user->getClass() === null) {
            return $response;
        }

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

    /**
     * Proces class access request
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function processClassAccessRequestAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $decision = ($data['decision'] === "true");

        $response->setDataVar("data", $this->classManager->processClassAccessRequest((int)$data['request'], $decision));

        return $response;
    }

    /**
     * Leave class
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function leaveClassAjaxAction(Request $request): Response
    {
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->userManager->leaveClass());

        return $response;
    }

    /**
     * Change class name
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function changeClassNameAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->classManager->changeClassName($data['name']));

        return $response;
    }

    /**
     * Create class group
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function createClassGroupAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->classManager->addGroupToClass($data['name']));

        return $response;
    }

    /**
     * Delete class group
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteClassGroupAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->classManager->deleteClassGroup((int)$data['group']));

        return $response;
    }

    /**
     * Add student to class group
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function addStudentToClassGroupAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->classManager->addUserToClassGroup((int)$data['group'], (int)$data['user'])
        );

        return $response;
    }

    /**
     * Delete student from class group
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteStudentFromClassGroupAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->classManager->deleteUserFromClassGroup((int)$data['group'], (int)$data['user'])
        );

        return $response;
    }

    /**
     * Add taught group to class group
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function addTaughtGroupToClassGroupAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->classManager->addTaughtGroupToClassGroup(
                (int)$data['group'],
                (int)$data['subject'],
                (int)$data['teacher']
            )
        );

        return $response;
    }

    /**
     * Delete taught group from class group
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteTaughtGroupFromClassGroupAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->classManager->deleteTaughtGroupFromClassGroup((int)$data['class-group'], (int)$data['taught-group'])
        );

        return $response;
    }

    /**
     * Add classroom
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function addClassroomAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->classManager->addClassroom(
                $data['name'],
                ($data['description'] !== "" ? $data['description'] : null)
            )
        );

        return $response;
    }

    /**
     * Delete classroom
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteClassroomAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->classManager->deleteClassroom((int)$data['classroom']));

        return $response;
    }

    /**
     * Add teacher
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function addTeacherAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar(
            "data",
            $this->classManager->addTeacher(
                $data['shortcut'],
                ($data['degree-before'] !== "" ? $data['degree-before'] : null),
                $data['full-name'],
                ($data['degree-after'] !== "" ? $data['degree-after'] : null)
            )
        );

        return $response;
    }

    /**
     * Delete teacher
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteTeacherAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->classManager->deleteTeacher((int)$data['teacher']));

        return $response;
    }

    /**
     * Add subject
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function addSubjectAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->classManager->addSubject($data['shortcut'], $data['name']));

        return $response;
    }

    /**
     * Delete subject
     *
     * @param \Mammoth\Http\Entity\Request $request
     *
     * @return \Mammoth\Http\Entity\Response
     */
    public function deleteSubjectAjaxAction(Request $request): Response
    {
        $data = $request->getPost();
        $response = $this->responseFactory->create($request)->setContentView("#code");

        $response->setDataVar("data", $this->classManager->deleteSubject((int)$data['subject']));

        return $response;
    }
}
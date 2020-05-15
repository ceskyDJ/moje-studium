<?php

declare(strict_types = 1);

namespace App\Model;

use App\Repository\Abstraction\IClassGroupRepository;
use App\Repository\Abstraction\IClassroomRepository;
use App\Repository\Abstraction\IClassSelectionRequestRepository;
use App\Repository\Abstraction\ISchoolClassRepository;
use App\Repository\Abstraction\ISchoolRepository;
use App\Repository\Abstraction\ISchoolSubjectRepository;
use App\Repository\Abstraction\ITaughtGroupRepository;
use App\Repository\Abstraction\ITeacherRepository;
use App\Repository\Abstraction\IUserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Mammoth\DI\DIClass;
use Mammoth\Templates\Abstraction\IMessageManager;
use function array_shift;
use function date;
use function explode;
use function implode;
use function json_encode;
use function mb_strlen;
use function preg_match;
use function substr;

/**
 * Manager for school classes
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Model
 */
class ClassManager
{
    use DIClass;

    /**
     * CSS class name for negative messages
     */
    private const NEGATIVE_MESSAGE = "negative";
    /**
     * CSS class name for positive messages
     */
    private const POSITIVE_MESSAGE = "positive";

    /**
     * @inject
     */
    private ISchoolRepository $schoolRepository;
    /**
     * @inject
     */
    private ISchoolClassRepository $classRepository;
    /**
     * @inject
     */
    private IUserRepository $userRepository;
    /**
     * @inject
     */
    private IClassGroupRepository $groupRepository;
    /**
     * @inject
     */
    private IClassSelectionRequestRepository $classSelectionRequestRepository;
    /**
     * @inject
     */
    private ISchoolSubjectRepository $subjectRepository;
    /**
     * @inject
     */
    private ITeacherRepository $teacherRepository;
    /**
     * @inject
     */
    private ITaughtGroupRepository $taughtGroupRepository;
    /**
     * @inject
     */
    private IClassroomRepository $classroomRepository;
    /**
     * @inject
     */
    private IMessageManager $messageManager;
    /**
     * @inject
     */
    private UserManager $userManager;

    /**
     * Returns all classes in school
     *
     * @param int $schoolId What school is it?
     *
     * @return string Classes of the school in JSON format
     */
    public function getAllClassesInSchoolForForm(int $schoolId): string
    {
        $school = $this->schoolRepository->getById($schoolId);
        $classes = $school->getClasses()->toArray();

        $resultArray = [];
        /**
         * @var $class \App\Entity\SchoolClass
         */
        foreach ($classes as $class) {
            $secondYear = (int)substr((string)$class->getStartYear(), 2, 2) + 1;

            $resultArray[] = [
                'id' => $class->getId(),
                'displayName' => "{$class->getName()} (od {$class->getStartYear()}/{$secondYear})",
            ];
        }

        /**
         * @var $item \App\Entity\SchoolClass
         */
        return json_encode($resultArray);
    }

    /**
     * Generates start years
     *
     * @return array Start years (minimum is 10 years ago)
     */
    public function generateStartYears(): array
    {
        $minimumYear = date("Y") - 10;

        $startYears = [];
        for ($i = 0; $i < 10; $i++) {
            $firstYear = $minimumYear + $i;
            $secondYear = $firstYear + 1;

            $startYears[] = "{$firstYear}/{$secondYear}";
        }

        return $startYears;
    }

    /**
     * Adds new class and sets it to logged in user
     *
     * @param int $schoolId ID of school owns this class
     * @param string $name Name (for ex. 1.A)
     * @param string $startYear Start year (for ex. 2019/2020)
     * @param string $studyLength Study length (for ex. 4 roky)
     *
     * @return bool Has it been successful?
     */
    public function addClass(int $schoolId, string $name, string $startYear, string $studyLength): bool
    {
        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        if ($user->getClass() !== null) {
            $this->messageManager->addMessage(
                "Již jsi v nějaké třídě, pro vytvoření nové třídy nejprve odejdi z té aktuální", self::NEGATIVE_MESSAGE);

            return false;
        }

        if (empty($schoolId) || empty($name) || empty($startYear) || empty($studyLength)) {
            $this->messageManager->addMessage("Nebyla vyplněna všechna pole", self::NEGATIVE_MESSAGE);

            return false;
        }

        if (($school = $this->schoolRepository->getById($schoolId)) === null) {
            $this->messageManager->addMessage("Vybraná škola neexistuje", self::NEGATIVE_MESSAGE);

            return false;
        }

        if (!(int)preg_match("%^\d{1,2}\.[A-Z]{1,2}$%", $name)) {
            $this->messageManager->addMessage(
                "Zadal jsi chybný tvar názvu třídy. Správně je např. 4.F",
                self::NEGATIVE_MESSAGE
            );

            return false;
        }

        $startYear = (int)substr($startYear, 0, 4);
        if (!(int)preg_match("%^\d{4}$%", (string)$startYear)) {
            $this->messageManager->addMessage("Formát počátečního školního roku není správný", self::NEGATIVE_MESSAGE);

            return false;
        }

        [$studyLength,] = explode(" ", $studyLength);
        if (($studyLength = (int)$studyLength) <= 0) {
            $this->messageManager->addMessage("Formát délky studia není správný", self::NEGATIVE_MESSAGE);

            return false;
        }

        // 4.A -> $yearOfStudies = 4, $label = A
        [$yearOfStudies, $label] = explode(".", $name);
        if ($yearOfStudies > $studyLength) {
            $yearString = ($yearOfStudies <= 4 ? "roky" : "let");
            $this->messageManager->addMessage(
                "Ročník v názvu třídy je vyšší než délka celého studia. Nechtěl/a jsi nastavit délku studia na {$yearOfStudies} {$yearString}?",
                self::NEGATIVE_MESSAGE
            );

            return false;
        }

        if (($startYear + $studyLength) < (int)date("Y") && $yearOfStudies !== $studyLength) {
            $this->messageManager->addMessage("Tvoje třída již dostudovala. Pokud ji stejně chceš přidat, zadej do názvu třídy poslední ročník ({$studyLength}.{$label})", self::NEGATIVE_MESSAGE);

            return false;
        }

        // Unique values
        if ($this->classRepository->getByNameSchoolAndStartYear($name, $school, $startYear) !== null) {
            $this->messageManager->addMessage("Tato třída již byla do systému přidána", self::NEGATIVE_MESSAGE);

            return false;
        }

        $class = $this->classRepository->add($name, $startYear, $studyLength, $school);
        $this->userRepository->selectClass((int)$user->getId(), $class);

        // Add default group (whole class) to class and the user to it
        $this->groupRepository->add(IClassGroupRepository::WHOLE_CLASS_GROUP, $class);
        $this->groupRepository->addUserToWholeClassGroup($user, $class);

        return true;
    }

    /**
     * Deletes existing class
     *
     * @param int $class Class's ID
     *
     * @return string JSON response
     */
    public function deleteClass(int $class): string
    {
        if (empty($class)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($class = $this->classRepository->getById($class)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Třída neexistuje",
                ]
            );
        }

        $this->classRepository->delete($class->getId());

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Processes class enter request
     *
     * @param int $request Request's ID
     * @param bool $decision Decision (allow or deny access)
     *
     * @return string JSON response
     */
    public function processClassAccessRequest(int $request, bool $decision): string
    {
        if (empty($request) || (empty($decision) && $decision !== false)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($request = $this->classSelectionRequestRepository->getById($request)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Neexistující požadavek na přístup do třídy",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        if ($user->getClass() === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nepatříš do žádné třídy",
                ]
            );
        }

        if ($request->getClass()->getId() !== $user->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Můžeš rozhodovat pouze o požadavcích na přístup do tvé třídy",
                ]
            );
        }

        $this->classSelectionRequestRepository->close($request->getId(), $decision);

        if ($decision === true) {
            $this->userRepository->selectClass((int)$request->getUser()->getId(), $request->getClass());
            $this->groupRepository->addUserToWholeClassGroup($request->getUser(), $request->getClass());
        }

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Changes class name
     *
     * @param string $name New class name
     *
     * @return string JSON response
     */
    public function changeClassName(string $name): string
    {
        if (empty($name)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        $class = $user->getClass();

        if ($name === $class->getName()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Zadal/a jsi stejný název třídy, jaký je již nastaven",
                ]
            );
        }

        if (!(int)preg_match("%^\d{1,2}\.[A-Z]{1,2}$%", $name)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Zadal/a jsi chybný tvar názvu třídy. Správně je např. 4.F",
                ]
            );
        }

        // 4.A -> $yearOfStudies = 4, $label = A
        [$yearOfStudies, $label] = explode(".", $name);
        $yearOfStudies = (int)$yearOfStudies;
        if ($yearOfStudies > $class->getStudyLength()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Ročník v názvu třídy je vyšší než délka celého studia.",
                ]
            );
        }

        $actualLength = date("Y") - $class->getStartYear();
        $actualLength = ($actualLength > $class->getStudyLength() ? $class->getStudyLength() : $actualLength);

        if ($yearOfStudies !== $actualLength) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Ročník v názvu třídy vzhledem k délce studia není správný. Správně je:  {$actualLength}",
                ]
            );
        }

        if ($this->classRepository->getByNameSchoolAndStartYear($name, $class->getSchool(), $class->getStartYear())
            !== null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Zadal/a jsi nejspíše chybný název třídy. Ve vaší škole je třída se stejným názvem a počátečním rokem.",
                ]
            );
        }

        $this->classRepository->edit($class->getId(), $name);

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Adds a new group to class
     *
     * @param string $name Group name
     *
     * @return string JSON response
     */
    public function addGroupToClass(string $name): string
    {
        if (empty($name)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (mb_strlen($name) > 20) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Maximální délka názvu skupiny je 20 znaků",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        $class = $user->getClass();

        if ($this->groupRepository->getByClassAndName($class, $name) !== null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Skupinu s tímto názvem již ve třídě máte",
                ]
            );
        }

        $group = $this->groupRepository->add($name, $class);

        return json_encode(
            [
                'success' => true,
                'id'      => $group->getId(),
            ]
        );
    }

    /**
     * Deletes class group
     *
     * @param int $group Group's ID
     *
     * @return string JSON response
     */
    public function deleteClassGroup(int $group): string
    {
        if (empty($group)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($group = $this->groupRepository->getById($group)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato skupina neexistuje",
                ]
            );
        }

        if ($group->getName() === IClassGroupRepository::WHOLE_CLASS_GROUP) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Výchozí skupinu pro celou třídu nelze odstranit",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        if ($group->getClass()->getId() !== $user->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato skupina nepatří do tvé třídy",
                ]
            );
        }

        $this->groupRepository->delete($group->getId());

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Adds user to class group
     *
     * @param int $group Group's ID
     * @param int $user User's ID
     *
     * @return string JSON response
     */
    public function addUserToClassGroup(int $group, int $user): string
    {
        if (empty($group) || empty($user)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($group = $this->groupRepository->getById($group)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato skupina neexistuje",
                ]
            );
        }

        if ($group->getName() === IClassGroupRepository::WHOLE_CLASS_GROUP) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Do výchozí skupiny není možné nikoho přidat",
                ]
            );
        }

        /**
         * @var $loggedInUser \App\Entity\User
         */
        $loggedInUser = $this->userManager->getUser();
        if ($group->getClass()->getId() !== $loggedInUser->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato skupina nepatří do tvé třídy",
                ]
            );
        }

        if (($user = $this->userRepository->getById($user)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Uživatel neexistuje",
                ]
            );
        }

        if ($group->getUsers()->contains($user)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tento uživatel již do dané skupiny patří",
                ]
            );
        }

        $this->groupRepository->addUser($group->getId(), $user);

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Deletes user from class group
     *
     * @param int $group Group's ID
     * @param int $user User's ID
     *
     * @return string JSON response
     */
    public function deleteUserFromClassGroup(int $group, int $user): string
    {
        if (empty($group) || empty($user)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($group = $this->groupRepository->getById($group)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato skupina neexistuje",
                ]
            );
        }

        if ($group->getName() === IClassGroupRepository::WHOLE_CLASS_GROUP) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Z výchozí skupiny není možné nikoho odstraňovat",
                ]
            );
        }

        /**
         * @var $loggedInUser \App\Entity\User
         */
        $loggedInUser = $this->userManager->getUser();
        if ($group->getClass()->getId() !== $loggedInUser->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato skupina nepatří do tvé třídy",
                ]
            );
        }

        if (($user = $this->userRepository->getById($user)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Uživatel neexistuje",
                ]
            );
        }

        if (!$group->getUsers()->contains($user)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tento uživatel nepatří do dané skupiny",
                ]
            );
        }

        $this->groupRepository->deleteUser($group->getId(), $user);

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Adds taught group to class group
     *
     * @param int $group Class group's ID
     * @param int $subject Subject's ID
     * @param int $teacher Teacher's ID
     *
     * @return string JSON response
     */
    public function addTaughtGroupToClassGroup(int $group, int $subject, int $teacher): string
    {
        if (empty($group) || empty($subject) || empty($teacher)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($group = $this->groupRepository->getById($group)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato skupina neexistuje",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        if ($group->getClass()->getId() !== $user->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato skupina nepatří do tvé třídy",
                ]
            );
        }

        if (($subject = $this->subjectRepository->getById($subject)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tento předmět neexistuje",
                ]
            );
        }

        if ($subject->getClass()->getId() !== $user->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tento předmět nepatří do tvé třídy",
                ]
            );
        }

        if (($teacher = $this->teacherRepository->getById($teacher)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tento učitel neexistuje",
                ]
            );
        }

        if ($teacher->getClass()->getId() !== $user->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tento učitel nepatří do tvé třídy",
                ]
            );
        }

        if ($this->taughtGroupRepository->getByClassGroupSubjectAndTeacher($group, $subject, $teacher) !== null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato vyučovací skupina již je v dané skupině",
                ]
            );
        }

        $taughtGroup = $this->taughtGroupRepository->add($group, $subject, $teacher);

        return json_encode(
            [
                'success' => true,
                'id'      => $taughtGroup->getId(),
            ]
        );
    }

    /**
     * Deletes taught group from class group
     *
     * @param int $classGroup Class group's ID
     * @param int $taughtGroup Taught group's ID
     *
     * @return string JSON response
     */
    public function deleteTaughtGroupFromClassGroup(int $classGroup, int $taughtGroup): string
    {
        if (empty($classGroup) || empty($taughtGroup)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($classGroup = $this->groupRepository->getById($classGroup)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato skupina neexistuje",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        if ($classGroup->getClass()->getId() !== $user->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato třídní skupina nepatří do tvé třídy",
                ]
            );
        }

        if (($taughtGroup = $this->taughtGroupRepository->getById($taughtGroup)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato vfyučovaná skupina neexistuje",
                ]
            );
        }

        $this->taughtGroupRepository->delete($taughtGroup->getId());

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Adds a new classroom
     *
     * @param string $name Name (on the door)
     * @param string|null $description Description
     *
     * @return string JSON response
     */
    public function addClassroom(string $name, ?string $description): string
    {
        if (empty($name)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (mb_strlen($name) > 8) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Maximální délka názvu je 8 znaků",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        $class = $user->getClass();

        try {
            $classroom = $this->classroomRepository->add($class, $name, $description);

            return json_encode(
                [
                    'success' => true,
                    'id'      => $classroom->getId(),
                ]
            );
        } catch (UniqueConstraintViolationException $e) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato učebna již byla tvé třídy přidána",
                ]
            );
        }
    }

    /**
     * Deletes classroom
     *
     * @param int $classroom Classroom's ID
     *
     * @return string JSON response
     */
    public function deleteClassroom(int $classroom): string
    {
        if (empty($classroom)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($classroom = $this->classroomRepository->getById($classroom)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato učebna neexistuje",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        if ($classroom->getClass()->getId() !== $user->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tato učebna nepatří do tvé třídy",
                ]
            );
        }

        $this->classroomRepository->delete($classroom->getId());

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Adds a teacher
     *
     * @param string $shortcut Shortcut (for ex. JIC)
     * @param string|null $degreeBefore Degree before name (with dot)
     * @param string $fullName Full name
     * @param string|null $degreeAfter Degree after name (with dot)
     *
     * @return string JSON response
     */
    public function addTeacher(string $shortcut, ?string $degreeBefore, string $fullName, ?string $degreeAfter): string
    {
        if (empty($shortcut) || empty($fullName)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (mb_strlen($shortcut) > 3) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Zkratka může mít maximálně 3 znaky",
                ]
            );
        }

        $nameParts = explode(" ", $fullName);
        $firstName = array_shift($nameParts);
        $lastName = implode(" ", $nameParts);

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        $class = $user->getClass();

        try {
            $teacher = $this->teacherRepository->add(
                $class,
                $firstName,
                $lastName,
                $degreeBefore,
                $degreeAfter,
                $shortcut
            );

            return json_encode(
                [
                    'success' => true,
                    'id'      => $teacher->getId(),
                ]
            );
        } catch (UniqueConstraintViolationException $e) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Učitel s touto zkratkou již byl přidán",
                ]
            );
        }
    }

    /**
     * Deletes a teacher
     *
     * @param int $teacher Teacher's ID
     *
     * @return string JSON response
     */
    public function deleteTeacher(int $teacher): string
    {
        if (empty($teacher)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($teacher = $this->teacherRepository->getById($teacher)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tento učitel neexistuje",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        if ($teacher->getClass()->getId() !== $user->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tento učitel nepatří do tvé třídy",
                ]
            );
        }

        $this->teacherRepository->delete($teacher->getId());

        return json_encode(
            [
                'success' => true,
            ]
        );
    }

    /**
     * Adds a subject
     *
     * @param string $shortcut Shortcut (for ex. MA)
     * @param string $name Name
     *
     * @return string JSON response
     */
    public function addSubject(string $shortcut, string $name): string
    {
        if (empty($shortcut) || empty($name)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (mb_strlen($shortcut) > 5) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Maximální délka zkratky je 5 znaků",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        $class = $user->getClass();

        try {
            $subject = $this->subjectRepository->add($class, $name, $shortcut);

            return json_encode(
                [
                    'success' => true,
                    'id'      => $subject->getId(),
                ]
            );
        } catch (UniqueConstraintViolationException $e) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Předmět s touto zkratkou již byl přidán",
                ]
            );
        }
    }

    /**
     * Deletes a subject
     *
     * @param int $subject Subject's ID
     *
     * @return string JSON response
     */
    public function deleteSubject(int $subject): string
    {
        if (empty($subject)) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Nebyla vyplněna všechna pole",
                ]
            );
        }

        if (($subject = $this->subjectRepository->getById($subject)) === null) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tento předmět neexistuje",
                ]
            );
        }

        /**
         * @var $user \App\Entity\User
         */
        $user = $this->userManager->getUser();
        if ($subject->getClass()->getId() !== $user->getClass()->getId()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => "Tento předmět nepatří do tvé třídy",
                ]
            );
        }

        $this->subjectRepository->delete($subject->getId());

        return json_encode(
            [
                'success' => true,
            ]
        );
    }
}
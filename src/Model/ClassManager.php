<?php

declare(strict_types = 1);

namespace App\Model;

use App\Repository\Abstraction\ISchoolClassRepository;
use App\Repository\Abstraction\ISchoolRepository;
use App\Repository\Abstraction\IUserRepository;
use Mammoth\DI\DIClass;
use Mammoth\Templates\Abstraction\IMessageManager;
use function date;
use function explode;
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

        // Unique values
        if ($this->classRepository->getByNameSchoolAndStartYear($name, $school, $startYear) !== null) {
            $this->messageManager->addMessage("Tato třída již byla do systému přidána", self::NEGATIVE_MESSAGE);

            return false;
        }

        $class = $this->classRepository->add($name, $startYear, $studyLength, $school);
        $this->userRepository->selectClass((int)$this->userManager->getUser()->getId(), $class);

        return true;
    }
}
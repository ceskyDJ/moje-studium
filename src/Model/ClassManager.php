<?php

declare(strict_types = 1);

namespace App\Model;

use App\Repository\Abstraction\ISchoolRepository;
use Tracy\Debugger;
use function array_reduce;
use function substr;

/**
 * Manager for school classes
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Model
 */
class ClassManager
{
    /**
     * @inject
     */
    private ISchoolRepository $schoolRepository;

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
                'displayName' => "{$class->getName()} (od {$class->getStartYear()}/{$secondYear})"
            ];
        }

        /**
         * @var $item \App\Entity\SchoolClass
         */
        return json_encode($resultArray);
    }
}
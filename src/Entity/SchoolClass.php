<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Full school class (not group!)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="classes", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="uq_classes_name_start_year_school", columns={"name", "start_year", "school_id"})
 * })
 * @ORM\Entity
 */
class SchoolClass
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="class_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private ?int $id = null;
    /**
     * @var string Name (for ex. 4.F)
     * @ORM\Column(name="name", type="string", length=4, nullable=false, options={  })
     */
    private string $name;
    /**
     * @var int Start year in the school - when it began
     * @ORM\Column(name="start_year", type="smallint", length=4, columnDefinition="YEAR NOT NULL", options={  })
     */
    private int $startYear;
    /**
     * @var int Length of study the school
     * @ORM\Column(name="study_length", type="smallint", length=3, nullable=false, options={ "unsigned": true })
     */
    private int $studyLength;
    /**
     * @ORM\ManyToOne(targetEntity="School", inversedBy="classes")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="school_id", nullable=false)
     * @var \App\Entity\School School, in what the class is
     */
    private School $school;

    /**
     * @ORM\OneToMany(targetEntity="Classroom", mappedBy="class")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\Classroom>
     */
    private Collection $classrooms;
    /**
     * @ORM\OneToMany(targetEntity="ClassGroup", mappedBy="class")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\ClassGroup>
     */
    private Collection $groups;
    /**
     * @ORM\OneToMany(targetEntity="SharedFile", mappedBy="targetClass")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SharedFile>
     */
    private Collection $sharedFiles;
    /**
     * @ORM\OneToMany(targetEntity="SharedNote", mappedBy="targetClass")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SharedNote>
     */
    private Collection $sharedNotes;
    /**
     * @ORM\OneToMany(targetEntity="SharedReminder", mappedBy="targetClass")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SharedReminder>
     */
    private Collection $sharedReminders;
    /**
     * @ORM\OneToMany(targetEntity="SchoolSubject", mappedBy="class")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\SchoolSubject>
     */
    private Collection $subjects;
    /**
     * @ORM\OneToMany(targetEntity="Teacher", mappedBy="class")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\Teacher>
     */
    private Collection $teachers;
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="class")
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\User>
     */
    private Collection $users;

    public function __construct()
    {
        $this->classrooms = new ArrayCollection;
        $this->groups = new ArrayCollection;
        $this->sharedFiles = new ArrayCollection;
        $this->sharedNotes = new ArrayCollection;
        $this->sharedReminders = new ArrayCollection;
        $this->subjects = new ArrayCollection;
        $this->teachers = new ArrayCollection;
        $this->users = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): SchoolClass
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): SchoolClass
    {
        $this->name = $name;

        return $this;
    }

    public function getStartYear(): int
    {
        return $this->startYear;
    }

    public function setStartYear(int $startYear): SchoolClass
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getStudyLength(): int
    {
        return $this->studyLength;
    }

    public function setStudyLength(int $studyLength): SchoolClass
    {
        $this->studyLength = $studyLength;

        return $this;
    }

    public function getSchool(): School
    {
        return $this->school;
    }

    public function setSchool(School $school): SchoolClass
    {
        $this->school = $school;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\Classroom>
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\Classroom>|\App\Entity\Classroom[] $classrooms
     *
     * @return \App\Entity\SchoolClass
     */
    public function setClassrooms(iterable $classrooms): SchoolClass
    {
        if (is_array($classrooms)) {
            $classrooms = new ArrayCollection($classrooms);
        }

        $this->classrooms = $classrooms;

        return $this;
    }

    public function addClassroom(Classroom $classroom): SchoolClass
    {
        $this->classrooms->add($classroom);

        return $this;
    }

    public function removeClassroom(Classroom $classroom): SchoolClass
    {
        $this->classrooms->removeElement($classroom);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\ClassGroup>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\ClassGroup>|\App\Entity\ClassGroup[] $groups
     *
     * @return \App\Entity\SchoolClass
     */
    public function setGroups(iterable $groups): SchoolClass
    {
        if (is_array($groups)) {
            $groups = new ArrayCollection($groups);
        }

        $this->groups = $groups;

        return $this;
    }

    public function addGroup(ClassGroup $groups): SchoolClass
    {
        $this->groups->add($groups);

        return $this;
    }

    public function removeGroup(ClassGroup $group): SchoolClass
    {
        $this->groups->removeElement($group);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\SharedFile>|\App\Entity\SharedFile
     */
    public function getSharedFiles(): Collection
    {
        return $this->sharedFiles;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\SharedFile>|\App\Entity\SharedFile[] $sharedFiles
     *
     * @return \App\Entity\SchoolClass
     */
    public function setSharedFiles(iterable $sharedFiles): SchoolClass
    {
        if (is_array($sharedFiles)) {
            $sharedFiles = new ArrayCollection($sharedFiles);
        }

        $this->sharedFiles = $sharedFiles;

        return $this;
    }

    public function addSharedFile(SharedFile $sharedFile): SchoolClass
    {
        $this->sharedFiles->add($sharedFile);

        return $this;
    }

    public function removeSharedFile(SharedFile $sharedFile): SchoolClass
    {
        $this->sharedFiles->removeElement($sharedFile);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\SharedNote>|\App\Entity\SharedNote[]
     */
    public function getSharedNotes(): Collection
    {
        return $this->sharedNotes;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\SharedNote>|\App\Entity\SharedNote[] $sharedNotes
     *
     * @return \App\Entity\SchoolClass
     */
    public function setSharedNotes(iterable $sharedNotes): SchoolClass
    {
        if (is_array($sharedNotes)) {
            $sharedNotes = new ArrayCollection($sharedNotes);
        }

        $this->sharedNotes = $sharedNotes;

        return $this;
    }

    public function addSharedNote(SharedNote $sharedNote): SchoolClass
    {
        $this->sharedNotes->add($sharedNote);

        return $this;
    }

    public function removeSharedNote(SharedNote $sharedNote): SchoolClass
    {
        $this->sharedNotes->removeElement($sharedNote);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\SharedReminder>|\App\Entity\SharedReminder[]
     */
    public function getSharedReminders(): Collection
    {
        return $this->sharedReminders;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\SharedReminder>|\App\Entity\SharedReminder[] $sharedReminders
     *
     * @return \App\Entity\SchoolClass
     */
    public function setSharedReminders(iterable $sharedReminders): SchoolClass
    {
        if (is_array($sharedReminders)) {
            $sharedReminders = new ArrayCollection($sharedReminders);
        }

        $this->sharedReminders = $sharedReminders;

        return $this;
    }

    public function addSharedReminder(SharedReminder $sharedReminder): SchoolClass
    {
        $this->sharedReminders->add($sharedReminder);

        return $this;
    }

    public function removeSharedReminder(SharedReminder $sharedReminder): SchoolClass
    {
        $this->sharedReminders->removeElement($sharedReminder);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\SchoolSubject>|\App\Entity\SchoolSubject[]
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\SchoolSubject>|\App\Entity\SchoolSubject[] $subjects
     *
     * @return \App\Entity\SchoolClass
     */
    public function setSubjects(iterable $subjects): SchoolClass
    {
        if (is_array($subjects)) {
            $subjects = new ArrayCollection($subjects);
        }

        $this->subjects = $subjects;

        return $this;
    }

    public function addSubject(SchoolSubject $subject): SchoolClass
    {
        $this->subjects->add($subject);

        return $this;
    }

    public function removeSubject(SchoolSubject $subject): SchoolClass
    {
        $this->subjects->removeElement($subject);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\Teacher>|\App\Entity\Teacher[]
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\Teacher>|\App\Entity\Teacher[] $teachers
     *
     * @return \App\Entity\SchoolClass
     */
    public function setTeachers(iterable $teachers): SchoolClass
    {
        if (is_array($teachers)) {
            $teachers = new ArrayCollection($teachers);
        }

        $this->teachers = $teachers;

        return $this;
    }

    public function addTeacher(Teacher $teacher): SchoolClass
    {
        $this->teachers->add($teacher);

        return $this;
    }

    public function removeTeacher(Teacher $teacher): SchoolClass
    {
        $this->teachers->removeElement($teacher);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\User>|\App\Entity\User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\User>|\App\Entity\User[] $users
     *
     * @return \App\Entity\SchoolClass
     */
    public function setUsers(iterable $users): SchoolClass
    {
        if (is_array($users)) {
            $users = new ArrayCollection($users);
        }

        $this->users = $users;

        return $this;
    }

    public function addUser(User $user): SchoolClass
    {
        $this->users->add($user);

        return $this;
    }

    public function removeUser(User $user): SchoolClass
    {
        $this->users->removeElement($user);

        return $this;
    }
}
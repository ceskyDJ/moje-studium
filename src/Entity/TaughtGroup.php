<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Virtual record for connecting teacher, subject and school group
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="taught_groups")
 * @ORM\Entity
 */
class TaughtGroup
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="taught_group_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity="ClassGroup", inversedBy="taughtGroups")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="group_id")
     * @var \App\Entity\ClassGroup Class group (specific part of class)
     */
    private ClassGroup $group;
    /**
     * @ORM\ManyToOne(targetEntity="SchoolSubject", inversedBy="taughtGroups")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="subject_id")
     * @var \App\Entity\SchoolSubject Subject
     */
    private SchoolSubject $subject;
    /**
     * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="taughtGroups")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="teacher_id")
     * @var \App\Entity\Teacher Teacher
     */
    private Teacher $teacher;

    /**
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="taughtGroup")
     *
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\Lesson>
     */
    private Collection $lessons;

    public function __construct()
    {
        $this->lessons = new ArrayCollection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): TaughtGroup
    {
        $this->id = $id;

        return $this;
    }

    public function getGroup(): ClassGroup
    {
        return $this->group;
    }

    public function setGroup(ClassGroup $group): TaughtGroup
    {
        $this->group = $group;

        return $this;
    }

    public function getSubject(): SchoolSubject
    {
        return $this->subject;
    }

    public function setSubject(SchoolSubject $subject): TaughtGroup
    {
        $this->subject = $subject;

        return $this;
    }

    public function getTeacher(): Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(Teacher $teacher): TaughtGroup
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\App\Entity\Lesson>|\App\Entity\Lesson[]
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\App\Entity\Lesson>|\App\Entity\Lesson[] $lessons
     *
     * @return \App\Entity\TaughtGroup
     */
    public function setLessons(iterable $lessons): TaughtGroup
    {
        if (is_array($lessons)) {
            $lessons = new ArrayCollection($lessons);
        }

        $this->lessons = $lessons;

        return $this;
    }

    public function addLesson(Lesson $lesson): TaughtGroup
    {
        $this->lessons->add($lesson);

        return $this;
    }

    public function removeLesson(Lesson $lesson): TaughtGroup
    {
        $this->lessons->removeElement($lesson);

        return $this;
    }
}
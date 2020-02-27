<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_array;

/**
 * Classroom
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 * @ORM\Table(name="classrooms")
 * @ORM\Entity
 */
class Classroom
{
    /**
     * @var int Identification number
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="classroom_id", type="integer", length=10, nullable=false, options={ "unsigned": true })
     */
    private int $id;
    /**
     * @var string Name - usually door number
     * @ORM\Column(name="name", type="string", length=8, nullable=false, options={  })
     */
    private string $name;
    /**
     * @var string|null Additional description for the classroom
     * @ORM\Column(name="description", type="string", length=50, nullable=true, options={  })
     */
    private ?string $description;
    /**
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="classrooms")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="class_id", nullable=false, onDelete="CASCADE")
     * @var \App\Entity\SchoolClass Class that is taught in the classroom
     */
    private SchoolClass $class;

    /**
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="classroom")
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

    public function setId(int $id): Classroom
    {
        $this->id = $id;

        return $this;
    }

    public function getClass(): SchoolClass
    {
        return $this->class;
    }

    public function setClass(SchoolClass $class): Classroom
    {
        $this->class = $class;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Classroom
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Classroom
    {
        $this->description = $description;

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
     * @return \App\Entity\Classroom
     */
    public function setLessons(iterable $lessons): Classroom
    {
        if (is_array($lessons)) {
            $lessons = new ArrayCollection($lessons);
        }

        $this->lessons = $lessons;

        return $this;
    }

    public function addLesson(Lesson $lesson): Classroom
    {
        $this->lessons->add($lesson);

        return $this;
    }

    public function removeLesson(Lesson $lesson): Classroom
    {
        $this->lessons->removeElement($lesson);

        return $this;
    }
}
<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Cocur\Slugify\Slugify;


/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 * @Vich\Uploadable
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="recipe", fileNameProperty="filename")
     */
    private $main_pic;

    /**
     * @ORM\Column(type="integer")
     */
    private $prep_time;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cook_time;

    /**
     * @ORM\Column(type="text")
     */
    private $stuff;

    /**
     * @ORM\OneToMany(targetEntity=Steps::class, mappedBy="recipe", orphanRemoval=true)
     */
    private $steps;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="integer")
     */
    private $persons;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     */
    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return File|null
     */
    public function getMainPic(): ?File
    {
        return $this->main_pic;
    }

    /**
     * @param File|null $main_pic
     */
    public function setMainPic(?File $main_pic): void
    {
        $this->main_pic = $main_pic;
    }



    public function getPrepTime(): ?int
    {
        return $this->prep_time;
    }

    public function setPrepTime(int $prep_time): self
    {
        $this->prep_time = $prep_time;

        return $this;
    }

    public function getCookTime(): ?int
    {
        return $this->cook_time;
    }

    public function setCookTime(?int $cook_time): self
    {
        $this->cook_time = $cook_time;

        return $this;
    }

    public function getStuff(): ?string
    {
        return $this->stuff;
    }

    public function setStuff(string $stuff): self
    {
        $this->stuff = $stuff;

        return $this;
    }

    /**
     * @return Collection|Steps[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Steps $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setRecipe($this);
        }

        return $this;
    }

    public function removeStep(Steps $step): self
    {
        if ($this->steps->contains($step)) {
            $this->steps->removeElement($step);
            // set the owning side to null (unless already changed)
            if ($step->getRecipe() === $this) {
                $step->setRecipe(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getNbSteps(): int
    {
        return count($this->steps);
    }

    public function getSlug(): string
    {
        $slugify = new Slugify();
        return $slugify->slugify($this->title);
    }

    public function getPersons(): ?int
    {
        return $this->persons;
    }

    public function setPersons(int $persons): self
    {
        $this->persons = $persons;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}

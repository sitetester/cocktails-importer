<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DrinksRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DrinksRepository::class)
 */
class Drinks
{
    /**
     * https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/reference/basic-mapping.html#identifier-generation-strategies
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $glass;

    /**
     * @ORM\Column(type="string")
     */
    private string $alcoholic;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $thumbnail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $instructions;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="drinks")
     * @ORM\JoinColumn(nullable=false)
     */
    private Category $category;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Drinks
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGlass(): string
    {
        return $this->glass;
    }

    public function setGlass(string $glass): self
    {
        $this->glass = $glass;

        return $this;
    }

    public function getAlcoholic(): string
    {
        return $this->alcoholic;
    }

    public function setAlcoholic(string $alcoholic): self
    {
        $this->alcoholic = $alcoholic;

        return $this;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getInstructions(): string
    {
        return $this->instructions;
    }

    public function setInstructions(string $instructions): Drinks
    {
        $this->instructions = $instructions;
        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}

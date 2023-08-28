<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getRecipe", "getReview"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getRecipe", "getReview"])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getRecipe", "getReview"])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(["getRecipe", "getReview"])]
    private ?\DateTimeInterface $prepTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(["getRecipe", "getReview"])]
    private ?\DateTimeInterface $cookingTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(["getRecipe", "getReview"])]
    private ?\DateTimeInterface $restTime = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getRecipe", "getReview"])]
    private ?string $ingredients = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getRecipe", "getReview"])]
    private ?string $instructions = null;

    #[ORM\Column]
    #[Groups(["getRecipe", "getReview"])]
    private ?bool $isOnlyForUser = null;

    #[ORM\ManyToMany(targetEntity: Allergen::class, mappedBy: 'recipes')]
    #[Groups(["getRecipe"])]
    private Collection $allergens;

    #[ORM\ManyToMany(targetEntity: Diet::class, mappedBy: 'recipes')]
    #[Groups(["getRecipe"])]
    private Collection $diets;

    #[ORM\Column(length: 255)]
    #[Groups(["getRecipe", "getReview"])]
    private ?string $picture = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Review::class, orphanRemoval: true)]
    #[Groups(["getRecipe"])]
    private Collection $reviews;


    public function __construct()
    {
        $this->allergens = new ArrayCollection();
        $this->diets = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrepTime(): ?\DateTimeInterface
    {
        return $this->prepTime;
    }

    public function setPrepTime(\DateTimeInterface $prepTime): static
    {
        $this->prepTime = $prepTime;

        return $this;
    }

    public function getCookingTime(): ?\DateTimeInterface
    {
        return $this->cookingTime;
    }

    public function setCookingTime(?\DateTimeInterface $cookingTime): static
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    public function getRestTime(): ?\DateTimeInterface
    {
        return $this->restTime;
    }

    public function setRestTime(?\DateTimeInterface $restTime): static
    {
        $this->restTime = $restTime;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(string $instructions): static
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function isIsOnlyForUser(): ?bool
    {
        return $this->isOnlyForUser;
    }

    public function setIsOnlyForUser(bool $isOnlyForUser): static
    {
        $this->isOnlyForUser = $isOnlyForUser;

        return $this;
    }

    /**
     * @return Collection<int, Allergen>
     */
    public function getAllergens(): Collection
    {
        return $this->allergens;
    }

    public function addAllergen(Allergen $allergen): static
    {
        if (!$this->allergens->contains($allergen)) {
            $this->allergens->add($allergen);
            $allergen->addRecipe($this);
        }

        return $this;
    }

    public function removeAllergen(Allergen $allergen): static
    {
        if ($this->allergens->removeElement($allergen)) {
            $allergen->removeRecipe($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Diet>
     */
    public function getDiets(): Collection
    {
        return $this->diets;
    }

    public function addDiet(Diet $diet): static
    {
        if (!$this->diets->contains($diet)) {
            $this->diets->add($diet);
            $diet->addRecipe($this);
        }

        return $this;
    }

    public function removeDiet(Diet $diet): static
    {
        if ($this->diets->removeElement($diet)) {
            $diet->removeRecipe($this);
        }

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setRecipe($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getRecipe() === $this) {
                $review->setRecipe(null);
            }
        }

        return $this;
    }

}

<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Img2Cat>
     */
    #[ORM\OneToMany(targetEntity: Img2Cat::class, mappedBy: 'category')]
    private Collection $img2Cats;

    public function __construct()
    {
        $this->img2Cats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Img2Cat>
     */
    public function getImg2Cats(): Collection
    {
        return $this->img2Cats;
    }

    public function addImg2Cat(Img2Cat $img2Cat): static
    {
        if (!$this->img2Cats->contains($img2Cat)) {
            $this->img2Cats->add($img2Cat);
            $img2Cat->setCategory($this);
        }

        return $this;
    }

    public function removeImg2Cat(Img2Cat $img2Cat): static
    {
        if ($this->img2Cats->removeElement($img2Cat)) {
            // set the owning side to null (unless already changed)
            if ($img2Cat->getCategory() === $this) {
                $img2Cat->setCategory(null);
            }
        }

        return $this;
    }
}

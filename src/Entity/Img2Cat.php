<?php

namespace App\Entity;

use App\Repository\Img2CatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: Img2CatRepository::class)]
class Img2Cat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'img2Cats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Image $img = null;

    #[ORM\ManyToOne(inversedBy: 'img2Cats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImg(): ?Image
    {
        return $this->img;
    }

    public function setImg(?Image $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}

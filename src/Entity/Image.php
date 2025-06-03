<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $Album = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column]
    private ?bool $visible = null;

    // If it is a cover image
    #[ORM\OneToOne(mappedBy: 'cover', cascade: ['persist', 'remove'])]
    private ?Album $albumCover = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Album $album = null;

    /**
     * @var Collection<int, Img2Cat>
     */
    #[ORM\OneToMany(targetEntity: Img2Cat::class, mappedBy: 'img')]
    private Collection $img2Cats;

    public function __construct()
    {
        $this->img2Cats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->Album;
    }

    public function setAlbum(?Album $Album): static
    {
        $this->Album = $Album;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return Collection<int, Img2Cat>
     */
    public function getCategory(): Collection
    {
        return $this->img2Cats;
    }

    public function addCategory(Img2Cat $category): static
    {
        if (!$this->img2Cats->contains($category)) {
            $this->img2Cats->add($category);
            $category->setImg($this);
        }

        return $this;
    }

    public function removeCategory(Img2Cat $category): static
    {
        if ($this->img2Cats->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getImg() === $this) {
                $category->setImg(null);
            }
        }

        return $this;
    }
}

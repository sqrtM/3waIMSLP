<?php

namespace App\Entity;

use App\Repository\FavoritesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoritesRepository::class)]
class Favorites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $imslpIndex = null;

    #[ORM\Column(length: 255)]
    private ?string $imslpId = null;

    #[ORM\Column]
    private ?int $type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $favoritedUserId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImslpIndex(): ?int
    {
        return $this->imslpIndex;
    }

    public function setImslpIndex(int $imslpIndex): static
    {
        $this->imslpIndex = $imslpIndex;

        return $this;
    }

    public function getImslpId(): ?string
    {
        return $this->imslpId;
    }

    public function setImslpId(string $imslpId): static
    {
        $this->imslpId = $imslpId;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFavoritedUser(): ?Users
    {
        return $this->favoritedUserId;
    }

    public function setFavoritedUser(?Users $favoritedUserId): static
    {
        $this->favoritedUserId = $favoritedUserId;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\FavoritesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoritesRepository::class)]
class Favorites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $api_id = null;

    #[ORM\Column]
    private ?int $type = null;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'favorites')]
    private Collection $favorited_users;

    public function __construct()
    {
        $this->favorited_users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiId(): ?int
    {
        return $this->api_id;
    }

    public function setApiId(int $api_id): static
    {
        $this->api_id = $api_id;

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

    /**
     * @return Collection<int, Users>
     */
    public function getFavoritedUsers(): Collection
    {
        return $this->favorited_users;
    }

    public function addFavoritedUser(Users $favoritedUser): static
    {
        if (!$this->favorited_users->contains($favoritedUser)) {
            $this->favorited_users->add($favoritedUser);
        }

        return $this;
    }

    public function removeFavoritedUser(Users $favoritedUser): static
    {
        $this->favorited_users->removeElement($favoritedUser);

        return $this;
    }
}

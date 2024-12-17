<?php

namespace App\Entity;

use App\Repository\ChambreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChambreRepository::class)]
class Chambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numChambre = null;

    #[ORM\Column]
    private ?int $etage = null;

    #[ORM\Column]
    private ?int $capaciteChambre = null;

    #[ORM\Column(length: 255)]
    private ?string $typeChambre = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'chambre')]
    private Collection $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumChambre(): ?int
    {
        return $this->numChambre;
    }

    public function setNumChambre(int $numChambre): static
    {
        $this->numChambre = $numChambre;

        return $this;
    }

    public function getEtage(): ?int
    {
        return $this->etage;
    }

    public function setEtage(int $etage): static
    {
        $this->etage = $etage;

        return $this;
    }

    public function getCapaciteChambre(): ?int
    {
        return $this->capaciteChambre;
    }

    public function setCapaciteChambre(int $capaciteChambre): static
    {
        $this->capaciteChambre = $capaciteChambre;

        return $this;
    }

    public function getTypeChambre(): ?string
    {
        return $this->typeChambre;
    }

    public function setTypeChambre(string $typeChambre): static
    {
        $this->typeChambre = $typeChambre;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setChambre($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getChambre() === $this) {
                $user->setChambre(null);
            }
        }

        return $this;
    }
}

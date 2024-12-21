<?php

namespace App\Entity;

use App\Repository\BlocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BlocRepository::class)]
class Bloc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_bloc", type: "integer")]
    private ?int $idBloc = null;

    #[ORM\Column(length: 1)] 
    #[Assert\NotBlank(message: "Le nom du bloc est obligatoire.")]
    #[Assert\Length(
    exactMessage: "Le nom du bloc doit contenir exactement {{ limit }} caractère.",
    min: 1,
    max: 1)]

    #[Assert\Regex(
    pattern: "/^[A-Z]$/",
    message: "Le nom du bloc doit être une seule lettre alphabétique majuscule (A-Z)")]

    private ?string $nomBloc = null;
    #[ORM\Column]
    #[Assert\NotNull(message: "La capacité du bloc est obligatoire.")]
    #[Assert\Positive(message: "La capacité doit être strictement positive.")]
    #[Assert\Range(
    min: 1,
    max: 1000,
    notInRangeMessage: "La capacité doit être comprise entre 1 et 1000.")]

    private ?int $capaciteBloc = null;

    /**
     * @var Collection<int, Chambre>
     */
    #[ORM\OneToMany(targetEntity: Chambre::class, mappedBy: 'bloc', orphanRemoval: true)]
    private Collection $chambres;

    public function __construct()
    {
        $this->chambres = new ArrayCollection();
    }

    public function getIdBloc(): ?int
    {
        return $this->idBloc;
    }

    public function getNomBloc(): ?string
    {
        return $this->nomBloc;
    }

    public function setNomBloc(string $nomBloc): static
    {
        $this->nomBloc = $nomBloc;

        return $this;
    }

    public function getCapaciteBloc(): ?int
    {
        return $this->capaciteBloc;
    }

    public function setCapaciteBloc(int $capaciteBloc): static
    {
        $this->capaciteBloc = $capaciteBloc;

        return $this;
    }

    /**
     * @return Collection<int, Chambre>
     */
    public function getChambres(): Collection
    {
        return $this->chambres;
    }

    public function addChambre(Chambre $chambre): static
    {
        if (!$this->chambres->contains($chambre)) {
            $this->chambres->add($chambre);
            $chambre->setBloc($this);
        }

        return $this;
    }

    public function removeChambre(Chambre $chambre): static
    {
        if ($this->chambres->removeElement($chambre)) {
            // set the owning side to null (unless already changed)
            if ($chambre->getBloc() === $this) {
                $chambre->setBloc(null);
            }
        }

        return $this;
    }
}

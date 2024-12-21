<?php

namespace App\Entity;

use App\Repository\ChambreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChambreRepository::class)]
class Chambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idChambre")]
    private ?int $idChambre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le numéro de la chambre est obligatoire.")]
    #[Assert\Length(
    min: 1,
    max: 8,
    minMessage: "Le numéro de la chambre doit contenir au moins 1 caractère.",
    maxMessage: "Le numéro de la chambre ne peut pas dépasser 8 caractères.")]

    #[Assert\Regex(
    pattern: "/^\d+$/",
    message: "Le numéro de la chambre doit uniquement contenir des chiffres.")]

    private ?string $numChambre = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "L'étage est obligatoire.")]
    #[Assert\PositiveOrZero(message: "L'étage doit être un nombre positif ou nul.")]
    #[Assert\Range(
        min: 0,
        max: 10,
        notInRangeMessage: "L'étage doit être compris entre 0 et 10 ."
    )]
    private ?int $etage = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La capacité de la chambre est obligatoire.")]
    #[Assert\Positive(message: "La capacité doit être strictement positive.")]
    private ?int $capacite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type de la chambre est obligatoire.")]
    #[Assert\Choice(
    choices: ['simple', 'double', 'suite'],
    message: "Le type de chambre doit être 'simple', 'double' ou 'suite'.")]

    #[Assert\Length(
    max: 10 ,
    maxMessage: "Le type de la chambre ne peut pas dépasser 10 caractères.")]

    private ?string $type = null;

    #[ORM\ManyToOne(targetEntity: Bloc::class, inversedBy: 'chambres')]
    #[ORM\JoinColumn(name: "bloc_id", referencedColumnName: "id_bloc", nullable: false)]
    private ?Bloc $bloc = null;

   

    public function getIdChambre(): ?int
    {
        return $this->idChambre;
    }

    public function getNumChambre(): ?string
    {
        return $this->numChambre;
    }

    public function setNumChambre(string $numChambre): static
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

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBloc(): ?Bloc
    {
        return $this->bloc;
    }

    public function setBloc(?Bloc $bloc): static
    {
        $this->bloc = $bloc;

        return $this;
    }

   
}

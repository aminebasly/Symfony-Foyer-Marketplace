<?php

namespace App\Entity;

use App\Repository\ReservationChambreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationChambreRepository::class)]
class ReservationChambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idReservationChambre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "L'année universitaire est obligatoire.")]
    #[Assert\Date(message: "L'année universitaire doit être au format valide (YYYY-MM-DD).")]
    #[Assert\GreaterThanOrEqual(
        value: "now",
        message: "L'année universitaire ne peut pas être dans le passé.")]
    
    private ?\DateTimeInterface $anneeUniversitaire = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le statut de la réservation doit être précisé.")]
    #[Assert\Type(
        type: "bool",
        message: "Le statut de la réservation doit être un booléen.")]
    
    private ?bool $estValide = null;

    #[ORM\ManyToOne(targetEntity: Chambre::class)]
    #[ORM\JoinColumn(name: "chambre_id", referencedColumnName: "idChambre", nullable: false)]
    #[Assert\NotNull(message: "La chambre associée est obligatoire.")]
    private ?Chambre $chambre = null;

    

    

    public function getIdReservationChambre(): ?int
    {
        return $this->idReservationChambre;
    }

    public function getAnneeUniversitaire(): ?\DateTimeInterface
    {
        return $this->anneeUniversitaire;
    }

    public function setAnneeUniversitaire(\DateTimeInterface $anneeUniversitaire): static
    {
        $this->anneeUniversitaire = $anneeUniversitaire;

        return $this;
    }

    public function isEstValide(): ?bool
    {
        return $this->estValide;
    }

    public function setEstValide(bool $estValide): static
    {
        $this->estValide = $estValide;

        return $this;
    }

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(Chambre $chambre): static
    {
        $this->chambre = $chambre;
        return $this;
    }
}

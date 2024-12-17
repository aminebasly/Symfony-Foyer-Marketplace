<?php

namespace App\Entity;

use App\Repository\ReservationMachineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationMachineRepository::class)]
class ReservationMachine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idReservationMachine = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateReservation = null;

    #[ORM\Column]
    private ?int $heureDReservation = null;

    #[ORM\Column]
    private ?int $duréeReservation = null;

    #[ORM\Column]
    private ?int $nbVetements = null;

    #[ORM\Column(length: 255)]
    private ?string $cycle = null;

    #[ORM\Column(nullable: true)]
    private ?int $degre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdReservationMachine(): ?int
    {
        return $this->idReservationMachine;
    }

    public function setIdReservationMachine(int $idReservationMachine): static
    {
        $this->idReservationMachine = $idReservationMachine;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeInterface $dateReservation): static
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getHeureDReservation(): ?int
    {
        return $this->heureDReservation;
    }

    public function setHeureDReservation(int $heureDReservation): static
    {
        $this->heureDReservation = $heureDReservation;

        return $this;
    }

    public function getDuréeReservation(): ?int
    {
        return $this->duréeReservation;
    }

    public function setDuréeReservation(int $duréeReservation): static
    {
        $this->duréeReservation = $duréeReservation;

        return $this;
    }

    public function getNbVetements(): ?int
    {
        return $this->nbVetements;
    }

    public function setNbVetements(int $nbVetements): static
    {
        $this->nbVetements = $nbVetements;

        return $this;
    }

    public function getCycle(): ?string
    {
        return $this->cycle;
    }

    public function setCycle(string $cycle): static
    {
        $this->cycle = $cycle;

        return $this;
    }

    public function getDegre(): ?int
    {
        return $this->degre;
    }

    public function setDegre(?int $degre): static
    {
        $this->degre = $degre;

        return $this;
    }
}

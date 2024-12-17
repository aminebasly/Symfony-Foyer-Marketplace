<?php

namespace App\Entity;

use App\Repository\MachineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MachineRepository::class)]
class Machine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $typeMachine = null;

    #[ORM\ManyToOne(inversedBy: 'machines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Laverie $laverie = null;

    #[ORM\Column]
    private ?bool $estReserve = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $heureDebut = null;

    #[ORM\Column(nullable: true)]
    private ?int $dureeReserve = null;

    #[ORM\Column(length: 255)]
    private ?string $statutMachine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeMachine(): ?string
    {
        return $this->typeMachine;
    }

    public function setTypeMachine(string $typeMachine): static
    {
        $this->typeMachine = $typeMachine;

        return $this;
    }

    public function getLaverie(): ?Laverie
    {
        return $this->laverie;
    }

    public function setLaverie(?Laverie $laverie): static
    {
        $this->laverie = $laverie;

        return $this;
    }

    public function isEstReserve(): ?bool
    {
        return $this->estReserve;
    }

    public function setEstReserve(bool $estReserve): static
    {
        $this->estReserve = $estReserve;

        return $this;
    }


    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(\DateTimeInterface $heureDebut): static
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }


    public function getDureeReserve(): ?int
    {
        return $this->dureeReserve;
    }

    public function setDureeReserve(?int $dureeReserve): static
    {
        $this->dureeReserve = $dureeReserve;

        return $this;
    }

    public function getStatutMachine(): ?string
    {
        return $this->statutMachine;
    }

    public function setStatutMachine(string $statutMachine): static
    {
        $this->statutMachine = $statutMachine;

        return $this;
    }
}

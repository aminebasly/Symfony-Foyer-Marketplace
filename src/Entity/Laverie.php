<?php

namespace App\Entity;

use App\Repository\LaverieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: LaverieRepository::class)]
class Laverie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idLaverie = null;

    #[ORM\Column(length: 255)]
    private ?string $nomLaverie = null;

    /**
     * @var Collection<int, Machine>
     */
    #[ORM\OneToMany(targetEntity: Machine::class, mappedBy: 'laverie')]
    private Collection $machines;

    public function __construct()
    {
        $this->machines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdLaverie(): ?int
    {
        return $this->idLaverie;
    }

    public function setIdLaverie(int $idLaverie): static
    {
        $this->idLaverie = $idLaverie;

        return $this;
    }

    public function getNomLaverie(): ?string
    {
        return $this->nomLaverie;
    }

    public function setNomLaverie(string $nomLaverie): static
    {
        $this->nomLaverie = $nomLaverie;

        return $this;
    }

    /**
     * @return Collection<int, Machine>
     */
    public function getMachines(): Collection
    {
        return $this->machines;
    }

    public function addMachine(Machine $machine): static
    {
        if (!$this->machines->contains($machine)) {
            $this->machines->add($machine);
            $machine->setLaverie($this);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): static
    {
        if ($this->machines->removeElement($machine)) {
            // set the owning side to null (unless already changed)
            if ($machine->getLaverie() === $this) {
                $machine->setLaverie(null);
            }
        }

        return $this;
    }
}

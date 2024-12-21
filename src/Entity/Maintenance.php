<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity]
class Maintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "La date de début est obligatoire.")]
    #[Assert\LessThan(propertyPath: "end_time", message: "La date de début doit être antérieure à la date de fin.")]
    private ?\DateTimeInterface $start_time = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "La date de fin est obligatoire.")]
    #[Assert\GreaterThan(propertyPath: "start_time", message: "La date de fin doit être postérieure à la date de début.")]
    private ?\DateTimeInterface $end_time = null;


    #[ORM\OneToMany(mappedBy: 'maintenance', targetEntity: Reclamation::class, cascade: ['persist', 'remove'])]
    private Collection $reclamations;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->setMaintenance($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // Unset the owning side if necessary
            if ($reclamation->getMaintenance() === $this) {
                $reclamation->setMaintenance(null);
            }
        }

        return $this;
    }
    public function validateMaintenance(Maintenance $maintenance, ValidatorInterface $validator)
{
    $errors = $validator->validate($maintenance);

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo $error->getMessage(); // Affiche les messages d'erreur
        }
    }
}
}

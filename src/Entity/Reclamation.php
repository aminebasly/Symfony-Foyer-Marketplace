<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La catégorie ne peut pas être vide.")]
    #[Assert\Length(
        max: 15,
        maxMessage: "La catégorie ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $categorie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'objet est obligatoire.")]
    #[Assert\Length(
        max: 15,
        maxMessage: "L'objet ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $objet = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description ne peut pas être vide.")]
    #[Assert\Length(
        max: 15,
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull(message: "La date de réclamation est obligatoire.")]
    #[Assert\Type(
        type: \DateTimeInterface::class,
        message: "La date doit être valide."
    )]
    private ?\DateTimeInterface $date_reclamation = null;

    #[ORM\ManyToOne(targetEntity: Maintenance::class, inversedBy: 'reclamations')]
    private ?Maintenance $maintenance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateReclamation(): ?\DateTimeInterface
    {
        return $this->date_reclamation;
    }

    public function setDateReclamation(\DateTimeInterface $date_reclamation): self
    {
        $this->date_reclamation = $date_reclamation;

        return $this;
    }

    public function getMaintenance(): ?Maintenance
    {
        return $this->maintenance;
    }

    public function setMaintenance(?Maintenance $maintenance): self
    {
        $this->maintenance = $maintenance;

        return $this;
    }

    public function createReclamation(Reclamation $reclamation, ValidatorInterface $validator): void
    {
        $errors = $validator->validate($reclamation);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo $error->getMessage();
            }
            return; // Arrêtez l'exécution si des erreurs sont trouvées
        }

        // Enregistrer l'entité si aucune erreur
        $this->entityManager->persist($reclamation);
        $this->entityManager->flush();
    }
}

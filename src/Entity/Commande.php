<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "La date de commande est obligatoire.")]
    #[Assert\Type("\DateTimeInterface", message: "La date de commande doit être une date valide.")]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    // #[Assert\NotNull(message: "La date de confirmation est obligatoire.")]
    // #[Assert\Type("\DateTimeInterface", message: "La date de confirmation doit être une date valide.")]
    // #[Assert\GreaterThan(propertyPath: "dateCommande", message: "La date de confirmation doit être postérieure à la date de commande.")]
    private ?\DateTimeInterface $dateConfirmation = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le total de la commande est obligatoire.")]
    #[Assert\Positive(message: "Le total de la commande doit être un montant positif.")]
    private ?float $totalCommande = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut de la commande est obligatoire.")]
    private ?string $statutCommande = null;

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'commandes')]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getDateConfirmation(): ?\DateTimeInterface
    {
        return $this->dateConfirmation;
    }

    public function setDateConfirmation(\DateTimeInterface $dateConfirmation): static
    {
        $this->dateConfirmation = $dateConfirmation;

        return $this;
    }

    public function getTotalCommande(): ?float
    {
        return $this->totalCommande;
    }

    public function setTotalCommande(float $totalCommande): static
    {
        $this->totalCommande = $totalCommande;

        return $this;
    }

    public function getStatutCommande(): ?string
    {
        return $this->statutCommande;
    }

    public function setStatutCommande(string $statutCommande): static
    {
        $this->statutCommande = $statutCommande;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->addCommande($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeCommande($this);
        }

        return $this;
    }
}

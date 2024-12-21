<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasse = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column(nullable: true)]
    private ?int $idEtudiant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specialite;

    #[ORM\Column(nullable: true)]
    private ?int $idTechnicien = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $service = null;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getIdEtudiant(): ?int
    {
        return $this->idEtudiant;
    }

    public function setIdEtudiant(?int $idEtudiant): static
    {
        $this->idEtudiant = $idEtudiant;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }


    public function setSpecialite(?string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getIdTechnicien(): ?int
    {
        return $this->idTechnicien;
    }

    public function setIdTechnicien(?int $idTechnicien): static
    {
        $this->idTechnicien = $idTechnicien;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): static
    {
        $this->service = $service;

        return $this;
    }


    public function getRoles(): array
    {
        return ['ROLE_USER']; // Exemple : rôle de base
    }

    public function getSalt(): ?string
    {
        return null; // Pas nécessaire si tu utilises bcrypt ou argon2i
    }

    // Implémentation de la méthode getUserIdentifier() au lieu de getUsername()
    public function getUserIdentifier(): string
    {
        return $this->email;  // L'email est utilisé comme identifiant
    }

    // Implémentation de la méthode eraseCredentials()
    public function eraseCredentials(): void
    {
        // Si tu stockes des informations sensibles (comme un mot de passe temporaire), efface-les ici
    }
    public function getPassword(): string
    {
        return $this->motDePasse;  // Retourner la propriété "motDePasse" ici
    }

    public function setPassword(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;
        return $this;
    }

}

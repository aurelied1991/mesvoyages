<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Contact
 *
 * @author aurel
 */
class Contact {
    //Déclaration des propriétés du formulaire. ? = null car vide au départ mais ne peux pas rester vide à la saisie
    #[Assert\NoBlank()]
    #[Assert\Lenght(min:2, max:100)]
    private ?string $nom;
    
    #[Assert\NoBlank()]
    #[Assert\Email()]
    private ?string $email;
    
    #[Assert\NoBlank()]
    private ?string $message;
    
    public function getNom(): ?string {
        return $this->nom;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function getMessage(): ?string {
        return $this->message;
    }

    public function setNom(?string $nom): self {
        $this->nom = $nom;
        return $this;
    }

    public function setEmail(?string $email): self {
        $this->email = $email;
        return $this;
    }

    public function setMessage(?string $message): self {
        $this->message = $message;
        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\LoginRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LoginRepository::class)]
class Login
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank([],'El campo no debe estar vacio')]
    private ?string $userName = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank([],'El campo no debe estar vacio')]
    private ?string $contrasena = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): self
    {
        $this->contrasena = $contrasena;

        return $this;
    }

}

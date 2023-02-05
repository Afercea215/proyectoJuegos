<?php

namespace App\Entity;

use App\Repository\ParticipaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipaRepository::class)]
class Participa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $invitacion = null;

    #[ORM\Column]
    private ?bool $presentado = null;

    #[ORM\ManyToOne(inversedBy: 'participas')]
    #[Assert\NotBlank([],'El campo debe estar relleno')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'participas')]
    #[Assert\NotBlank([],'El campo debe estar relleno')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Evento $evento = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvitacion(): ?string
    {
        return $this->invitacion;
    }

    public function setInvitacion(string $invitacion): self
    {
        $this->invitacion = $invitacion;

        return $this;
    }

    public function isPresentado(): ?bool
    {
        return $this->presentado;
    }

    public function setPresentado(bool $presentado): self
    {
        $this->presentado = $presentado;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEvento(): ?Evento
    {
        return $this->evento;
    }

    public function setEvento(?Evento $evento): self
    {
        $this->evento = $evento;

        return $this;
    }
}

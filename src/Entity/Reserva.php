<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fini = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaAnul = null;

    #[ORM\Column]
    private ?bool $presentado = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tramo $tramoIni = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tramo $tramoFin = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Juego $juego = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mesa $mesa = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFini(): ?\DateTimeInterface
    {
        return $this->fini;
    }

    public function setFini(\DateTimeInterface $fini): self
    {
        $this->fini = $fini;

        return $this;
    }

    public function getFechaAnul(): ?\DateTimeInterface
    {
        return $this->fechaAnul;
    }

    public function setFechaAnul(?\DateTimeInterface $fechaAnul): self
    {
        $this->fechaAnul = $fechaAnul;

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

    public function getTramoIni(): ?Tramo
    {
        return $this->tramoIni;
    }

    public function setTramoIni(?Tramo $tramoIni): self
    {
        $this->tramoIni = $tramoIni;

        return $this;
    }

    public function getTramoFin(): ?Tramo
    {
        return $this->tramoFin;
    }

    public function setTramoFin(?Tramo $tramoFin): self
    {
        $this->tramoFin = $tramoFin;

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

    public function getJuego(): ?Juego
    {
        return $this->juego;
    }

    public function setJuego(?Juego $juego): self
    {
        $this->juego = $juego;

        return $this;
    }

    public function getMesa(): ?mesa
    {
        return $this->mesa;
    }

    public function setMesa(?mesa $mesa): self
    {
        $this->mesa = $mesa;

        return $this;
    }
}

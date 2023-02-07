<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\NewReservaController;
use App\Repository\ReservaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(/* operations: [
    new Post(
        name: 'newReserva',
        uriTemplate: '/reservas',
        controller: NewReservaController::class,
    )
] */),
ApiFilter(
    DateFilter::class,
    properties: ['fecha' => DateFilter::EXCLUDE_NULL]
)]
#[ORM\Entity(repositoryClass: ReservaRepository::class)]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank([],'El campo debe estar relleno')]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[ORM\JoinColumn(nullable: true)]
    private ?\DateTimeInterface $fechaAnul = null;

    #[ORM\Column]
    #[ORM\JoinColumn(nullable: true)]
    private ?bool $presentado = null;

    #[ORM\ManyToOne]
    #[Assert\NotBlank([],'El campo debe estar relleno')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tramo $tramo = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[Assert\NotBlank([],'El campo debe estar relleno')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[Assert\NotBlank([],'El campo debe estar relleno')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Juego $juego = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[Assert\NotBlank([],'El campo debe estar relleno')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mesa $mesa = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

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

    public function getTramo(): ?Tramo
    {
        return $this->tramo;
    }

    public function setTramo(?Tramo $tramo): self
    {
        $this->tramo = $tramo;

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

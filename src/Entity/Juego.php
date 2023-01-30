<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\JuegoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JuegoRepository::class)]
class Juego
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ancho = null;

    #[ORM\Column]
    private ?int $longitud = null;

    #[ORM\Column]
    private ?int $minJuga = null;

    #[ORM\Column]
    private ?int $maxJuga = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $editorial = null;

    #[ORM\OneToMany(mappedBy: 'juego', targetEntity: Reserva::class)]
    private Collection $reservas;

    #[ORM\ManyToMany(targetEntity: evento::class, inversedBy: 'juegos')]
    private Collection $eventos;

    public function __construct()
    {
        $this->reservas = new ArrayCollection();
        $this->eventos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAncho(): ?int
    {
        return $this->ancho;
    }

    public function setAncho(int $ancho): self
    {
        $this->ancho = $ancho;

        return $this;
    }

    public function getLongitud(): ?int
    {
        return $this->longitud;
    }

    public function setLongitud(int $longitud): self
    {
        $this->longitud = $longitud;

        return $this;
    }

    public function getMinJuga(): ?int
    {
        return $this->minJuga;
    }

    public function setMinJuga(int $minJuga): self
    {
        $this->minJuga = $minJuga;

        return $this;
    }

    public function getMaxJuga(): ?int
    {
        return $this->maxJuga;
    }

    public function setMaxJuga(int $maxJuga): self
    {
        $this->maxJuga = $maxJuga;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getEditorial(): ?string
    {
        return $this->editorial;
    }

    public function setEditorial(?string $editorial): self
    {
        $this->editorial = $editorial;

        return $this;
    }

    /**
     * @return Collection<int, Reserva>
     */
    public function getReservas(): Collection
    {
        return $this->reservas;
    }

    public function addReserva(Reserva $reserva): self
    {
        if (!$this->reservas->contains($reserva)) {
            $this->reservas->add($reserva);
            $reserva->setJuego($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getJuego() === $this) {
                $reserva->setJuego(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, evento>
     */
    public function getEventos(): Collection
    {
        return $this->eventos;
    }

    public function addEvento(evento $evento): self
    {
        if (!$this->eventos->contains($evento)) {
            $this->eventos->add($evento);
        }

        return $this;
    }

    public function removeEvento(evento $evento): self
    {
        $this->eventos->removeElement($evento);

        return $this;
    }
}

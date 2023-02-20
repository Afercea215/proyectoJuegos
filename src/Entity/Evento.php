<?php

namespace App\Entity;

use App\Repository\EventoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventoRepository::class)]
class Evento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank([],'El campo no debe estar vacio')]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $descrip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank([],'El campo no debe estar vacio')]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\OneToMany(mappedBy: 'evento', targetEntity: Participa::class)]
    private Collection $participas;

    #[ORM\ManyToMany(targetEntity: Juego::class, mappedBy: 'eventos')]
    private Collection $juegos;

    public function __construct()
    {
        $this->participas = new ArrayCollection();
        $this->juegos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescrip(): ?string
    {
        return $this->descrip;
    }

    public function setDescrip(string $descrip): self
    {
        $this->descrip = $descrip;

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

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return Collection<int, Participa>
     */
    public function getParticipas(): Collection
    {
        return $this->participas;
    }

    public function addParticipa(Participa $participa): self
    {
        if (!$this->participas->contains($participa)) {
            $this->participas->add($participa);
            $participa->setEvento($this);
        }

        return $this;
    }

    public function removeParticipa(Participa $participa): self
    {
        if ($this->participas->removeElement($participa)) {
            // set the owning side to null (unless already changed)
            if ($participa->getEvento() === $this) {
                $participa->setEvento(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Juego>
     */
    public function getJuegos(): Collection
    {
        return $this->juegos;
    }

    public function addJuego(Juego $juego): self
    {
        if (!$this->juegos->contains($juego)) {
            $this->juegos->add($juego);
            $juego->addEvento($this);
        }

        return $this;
    }

    public function removeJuego(Juego $juego): self
    {
        if ($this->juegos->removeElement($juego)) {
            $juego->removeEvento($this);
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\MesaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(normalizationContext: ['skip_null_values' => false])]
#[ApiFilter(ExistsFilter::class, properties: [
    'x'
])]
#[ORM\Entity(repositoryClass: MesaRepository::class)]
class Mesa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    /**
     * Ancho de la mesa en cm
     */
    private ?int $ancho = null;

    #[ORM\Column]
    /**
     * Longitud de la mesa en cm
     */
    private ?int $longitud = null;

    #[ORM\Column(nullable: true)]
    /**
     * Posicion x en cm
     */
    private ?int $x = null;

    
    #[ORM\Column(nullable: true)]
    /**
     * Posicion y en cm
     */
    private ?int $y = null;

    #[ORM\OneToMany(mappedBy: 'mesa', targetEntity: Reserva::class)]
    private Collection $reservas;

    public function __construct()
    {
        $this->reservas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->ancho." - ".$this->longitud;
    }

    public function objToArray(): array
    {
        return [
            'id' => $this->getId(),
            'ancho' => $this->getId(),
            'longitud' => $this->getId(),
            'x' => $this->getId(),
            'y' => $this->getId(),
        ];
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

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(?int $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(?int $y): self
    {
        $this->y = $y;

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
            $reserva->setMesa($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getMesa() === $this) {
                $reserva->setMesa(null);
            }
        }

        return $this;
    }

}

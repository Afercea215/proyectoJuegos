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

    #[Assert\Range([
        'min' => 50,
        'max' => 300,
        'notInRangeMessage' => 'El ancho de la mesa debe ser como minimo {{ min }}cm y como maximo {{ max }}cm.',
        ])]
        #[ORM\Column]
        /**
         * Ancho de la mesa en cm
         */
    private ?int $ancho = null;
    
    #[Assert\Range([
        'min' => 50,
        'max' => 300,
        'notInRangeMessage' => 'La longitud de la mesa debe ser como minimo {{ min }}cm y como maximo {{ max }}cm.',
        ])]
        #[ORM\Column]
        /**
         * Longitud de la mesa en cm
         */
        private ?int $longitud = null;
        
        #[Assert\PositiveOrZero([],'El valor debe ser positivo')]
        #[ORM\Column(nullable: true)]
        /**
         * Posicion x en cm
         */
        private ?int $x = null;
        
        
        #[Assert\PositiveOrZero([],'El valor debe ser positivo')]
        #[ORM\Column(nullable: true)]
        /**
         * Posicion y en cm
         */
        private ?int $y = null;
        
        #[ORM\OneToMany(mappedBy: 'mesa', targetEntity: Reserva::class)]
        private Collection $reservas;
        
        #[ORM\OneToMany(mappedBy: 'mesa', targetEntity: Disposicion::class)]
        private Collection $disposicions;
        
        public function __construct()
        {
        $this->reservas = new ArrayCollection();
        $this->disposicions = new ArrayCollection();
    }

    public function __toString()
    {
        return 'Id : '.$this->id.', anch->'.$this->ancho.' long->'.$this->longitud;
    }

    /* public function __toString()
    {
        return $this->ancho." - ".$this->longitud;
    } */

    public function objToArray(): array
    {
        return [
            'id' => $this->getId(),
            'ancho' => $this->getAncho(),
            'longitud' => $this->getLongitud(),
            'x' => $this->getX(),
            'y' => $this->getY(),
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

    /**
     * @return Collection<int, Disposicion>
     */
    public function getDisposicions(): Collection
    {
        return $this->disposicions;
    }

    public function addDisposicion(Disposicion $disposicion): self
    {
        if (!$this->disposicions->contains($disposicion)) {
            $this->disposicions->add($disposicion);
            $disposicion->setMesa($this);
        }

        return $this;
    }

    public function removeDisposicion(Disposicion $disposicion): self
    {
        if ($this->disposicions->removeElement($disposicion)) {
            // set the owning side to null (unless already changed)
            if ($disposicion->getMesa() === $this) {
                $disposicion->setMesa(null);
            }
        }

        return $this;
    }

    
}

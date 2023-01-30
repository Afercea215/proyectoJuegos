<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reserva::class)]
    private Collection $reservas;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Participa::class)]
    private Collection $participas;

    public function __construct()
    {
        $this->reservas = new ArrayCollection();
        $this->participas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $reserva->setUser($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getUser() === $this) {
                $reserva->setUser(null);
            }
        }

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
            $participa->setUser($this);
        }

        return $this;
    }

    public function removeParticipa(Participa $participa): self
    {
        if ($this->participas->removeElement($participa)) {
            // set the owning side to null (unless already changed)
            if ($participa->getUser() === $this) {
                $participa->setUser(null);
            }
        }

        return $this;
    }
}

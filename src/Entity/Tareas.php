<?php

namespace App\Entity;

use App\Repository\TareasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TareasRepository::class)]
class Tareas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    private ?string $prioridad = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    /**
     * @var Collection<int, TareasUsuario>
     */
    #[ORM\OneToMany(targetEntity: TareasUsuario::class, mappedBy: 'idTarea')]
    private Collection $tareasUsuarios;

    public function __construct()
    {
        $this->tareasUsuarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPrioridad(): ?string
    {
        return $this->prioridad;
    }

    public function setPrioridad(string $prioridad): static
    {
        $this->prioridad = $prioridad;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return Collection<int, TareasUsuario>
     */
    public function getTareasUsuarios(): Collection
    {
        return $this->tareasUsuarios;
    }

    public function addTareasUsuario(TareasUsuario $tareasUsuario): static
    {
        if (!$this->tareasUsuarios->contains($tareasUsuario)) {
            $this->tareasUsuarios->add($tareasUsuario);
            $tareasUsuario->setIdTarea($this);
        }

        return $this;
    }

    public function removeTareasUsuario(TareasUsuario $tareasUsuario): static
    {
        if ($this->tareasUsuarios->removeElement($tareasUsuario)) {
            // set the owning side to null (unless already changed)
            if ($tareasUsuario->getIdTarea() === $this) {
                $tareasUsuario->setIdTarea(null);
            }
        }

        
        return $this;
    }
}

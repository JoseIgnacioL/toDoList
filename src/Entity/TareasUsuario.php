<?php

namespace App\Entity;

use App\Repository\TareasUsuarioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TareasUsuarioRepository::class)]
class TareasUsuario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tareasUsuarios')]
    private ?Usuarios $idUsuario = null;

    #[ORM\ManyToOne(inversedBy: 'tareasUsuarios')]
   
    
    private ?Tareas $idTarea = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsuario(): ?Usuarios
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(?Usuarios $idUsuario): static
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    public function getIdTarea(): ?Tareas
    {
        return $this->idTarea;
    }

    public function setIdTarea(?Tareas $idTarea): static
    {
        $this->idTarea = $idTarea;

        return $this;
    }
}

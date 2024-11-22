<?php

namespace App\Entity;

use App\Repository\UsuariosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuariosRepository::class)]
class Usuarios implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apellido = null;

    #[ORM\Column(length: 255)]
    private ?string $coreo = null;

    #[ORM\Column(length: 255)]

    private ?string $rol = null;

    #[ORM\Column(length: 255)]
    
    private ?string $contrasenyia = null;

    /**
     * @var Collection<int, TareasUsuario>
     */
    #[ORM\OneToMany(targetEntity: TareasUsuario::class, mappedBy: 'idUsuario')]
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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(?string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getCoreo(): ?string
    {
        return $this->coreo;
    }

    public function setCoreo(string $coreo): static
    {
        $this->coreo = $coreo;

        return $this;
    }

    public function getContrasenyia(): ?string
    {
        return $this->contrasenyia;
    }

    public function setContrasenyia(string $contrasenyia): static
    {
        $this->contrasenyia = $contrasenyia;

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
            $tareasUsuario->setIdUsuario($this);
        }

        return $this;
    }

    public function removeTareasUsuario(TareasUsuario $tareasUsuario): static
    {
        if ($this->tareasUsuarios->removeElement($tareasUsuario)) {
            // set the owning side to null (unless already changed)
            if ($tareasUsuario->getIdUsuario() === $this) {
                $tareasUsuario->setIdUsuario(null);
            }
        }

        return $this;
    }

    
    public function getUsername(): string
    {
        return $this->coreo; 
    }

   
    public function getPassword(): string
    {
        return $this->contrasenyia;
    }


    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;

        return $this;
    }


}

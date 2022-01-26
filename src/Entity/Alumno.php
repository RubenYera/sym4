<?php

namespace App\Entity;

use App\Repository\AlumnoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlumnoRepository::class)]
class Alumno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nombre;

    #[ORM\ManyToMany(targetEntity: Asignatura::class, inversedBy: 'alumnos')]
    private $Asignatura;

    public function __construct()
    {
        $this->Asignatura = new ArrayCollection();
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

    /**
     * @return Collection|Asignatura[]
     */
    public function getAsignatura(): Collection
    {
        return $this->Asignatura;
    }

    public function addAsignatura(Asignatura $asignatura): self
    {
        if (!$this->Asignatura->contains($asignatura)) {
            $this->Asignatura[] = $asignatura;
        }

        return $this;
    }

    public function removeAsignatura(Asignatura $asignatura): self
    {
        $this->Asignatura->removeElement($asignatura);

        return $this;
    }
}

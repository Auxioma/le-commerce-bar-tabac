<?php

namespace App\Entity;

use App\Repository\HoraireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoraireRepository::class)]
class Horaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Jours = null;

    #[ORM\Column(length: 255)]
    private ?string $HoraireOuverture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJours(): ?string
    {
        return $this->Jours;
    }

    public function setJours(string $Jours): static
    {
        $this->Jours = $Jours;

        return $this;
    }

    public function getHoraireOuverture(): ?string
    {
        return $this->HoraireOuverture;
    }

    public function setHoraireOuverture(string $HoraireOuverture): static
    {
        $this->HoraireOuverture = $HoraireOuverture;

        return $this;
    }
}

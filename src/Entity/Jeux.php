<?php

namespace App\Entity;

use App\Repository\JeuxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JeuxRepository::class)]
class Jeux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Label = null;

    #[ORM\Column(length: 255)]
    private ?string $Value = null;

    #[ORM\Column(length: 255)]
    private ?string $Question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->Label;
    }

    public function setLabel(string $Label): static
    {
        $this->Label = $Label;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->Value;
    }

    public function setValue(string $Value): static
    {
        $this->Value = $Value;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->Question;
    }

    public function setQuestion(string $Question): static
    {
        $this->Question = $Question;

        return $this;
    }
}

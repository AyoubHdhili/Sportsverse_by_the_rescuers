<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Message;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $duree = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $coach_id = null;


    #[ORM\Column(length: 255)]
    private ?string $adresse_client = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    private ?Emplacement $Emplacement = null;

    #[ORM\Column(length: 255,nullable:true)]
    #[Assert\NotBlank(message:"S'il vous plait Mettez un message au coach")]
    private ?string $message = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getCoach_Id(): ?user
    {
        return $this->coach_id;
    }

    public function setCoach_Id(?user $client_id): self
    {
        $this->coach_id = $client_id;

        return $this;
    }

    public function getAdresse_Client(): ?string
    {
        return $this->adresse_client;
    }

    public function setAdresse_Client(string $adresse_client): self
    {
        $this->adresse_client = $adresse_client;

        return $this;
    }

    public function getEmplacement(): ?Emplacement
    {
        return $this->Emplacement;
    }

    public function setEmplacement(?Emplacement $Emplacement): self
    {
        $this->Emplacement = $Emplacement;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}

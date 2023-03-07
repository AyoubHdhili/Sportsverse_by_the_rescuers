<?php

namespace App\Entity;

use App\Repository\CvRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CvRepository::class)]
class Cv
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Certification est obligatoire")]
    private ?string $certification = null;

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message: "Description est obligatoire")]

    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Tarif est obligatoire")]
    #[Assert\Range(
        min: 5,
        max: 100,
        notInRangeMessage: 'Ton tarif doit etre entre {{ min }} DT et {{ max }} DT',
    )]
    private ?float $tarif = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Duree d'experience est obligatoire"),]
    #[Assert\Range(
        min: 1,
        max: 20,
        notInRangeMessage: 'Ton experience doit etre entre {{ min }} ans et {{ max }} annees',
    )]

    private ?int $duree_experience = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Niveau d'experience est obligatoire")]

    private ?string $level = null;

    #[ORM\ManyToMany(targetEntity: Activite::class, inversedBy: 'cvs')]
    private Collection $activites;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCertification(): ?string
    {
        return $this->certification;
    }

    public function setCertification(string $certification): self
    {
        $this->certification = $certification;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }



    public function getduree_experience(): ?int
    {
        return $this->duree_experience;
    }
    public function getDureeExperience(): ?int
    {
        return $this->duree_experience;
    }

    public function setDureeExperience(int $duree_experience): self
    {
        $this->duree_experience = $duree_experience;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }
    public function __toString()
    {
        return (string) $this->description;
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites->add($activite);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        $this->activites->removeElement($activite);

        return $this;
    }
}

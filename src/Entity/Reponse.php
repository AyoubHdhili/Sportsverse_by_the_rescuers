<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponse
 *
 * @ORM\Table(name="reponse", indexes={@ORM\Index(name="id_reclamation", columns={"id_reclamation"})})
 * @ORM\Entity
 */
class Reponse
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message:" *description manquant")]
    #[Assert\Length(min:5,minMessage:" *description ne contient pas le minimum des caractères.")]
    
    private $description;

    /**
     * @var \Reclamation
     *
     * @ORM\ManyToOne(targetEntity="Reclamation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_reclamation", referencedColumnName="id")
     * })
     */
    private $idReclamation;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdReclamation(): ?Reclamation
    {
        return $this->idReclamation;
    }

    public function setIdReclamation(?Reclamation $idReclamation): self
    {
        $this->idReclamation = $idReclamation;

        return $this;
    }


}

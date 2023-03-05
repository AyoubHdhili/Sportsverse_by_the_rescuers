<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\BarChart;
/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class Reclamation
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
     * @ORM\Column(name="sujet", type="string", length=255, nullable=false)
      * @Groups("posts:read")
     * @Groups("reclamations")
     * */ 
    #[Assert\NotBlank(message:" *suujet manquant")]
    #[Assert\Length(min:5,minMessage:" *sujet ne contient pas le minimum des caractères.")]

    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Groups("posts:read")
     * @Groups("reclamations")
     * */
    #[Assert\NotBlank(message:" *description manquant")]
    #[Assert\Length(min:5,minMessage:" *description ne contient pas le minimum des caractères.")]

    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=true, options={"default"="en cours"})
     * @Groups("posts:read")
     * @Groups("reclamations")
     */

    private $etat = 'en cours ';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     * @Groups("posts:read")
     * @Groups("reclamations")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_client", type="string", length=255, nullable=false)
     * @Groups("posts:read")
     * @Groups("reclamations")
     */
    #[Assert\NotBlank(message:" *nom manquant")]
     private $nomClient; 

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     *   * @Groups("posts:read")
     * @Groups("reclamations")
     * */ 
    
    private $idUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
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

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): self
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }
    public function __construct()
    {
        $this->date = new \DateTime('now');
    }
    public function __toString()
    {
        return $this->sujet;
    }

}

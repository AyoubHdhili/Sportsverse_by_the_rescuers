<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Seance
 *
 * @ORM\Table(name="seance", indexes={@ORM\Index(name="IDX_DF7DFD0EDC2902E0", columns={"client_id_id"})})
 * @ORM\Entity
 */
class Seance
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $etat = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="durée", type="string", length=255, nullable=false)
     */
    private $durée;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id_id", referencedColumnName="id")
     * })
     */
    private $clientId;

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

    public function getDurée(): ?string
    {
        return $this->durée;
    }

    public function setDurée(string $durée): self
    {
        $this->durée = $durée;

        return $this;
    }

    public function getClientId(): ?User
    {
        return $this->clientId;
    }

    public function setClientId(?User $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }


}

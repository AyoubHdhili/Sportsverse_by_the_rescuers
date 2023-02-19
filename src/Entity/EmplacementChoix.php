<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmplacementChoix
 *
 * @ORM\Table(name="emplacement_choix")
 * @ORM\Entity
 */
class EmplacementChoix
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
     * @ORM\Column(name="governorat", type="string", length=255, nullable=false)
     */
    private $governorat;

    /**
     * @var string
     *
     * @ORM\Column(name="delegation", type="string", length=255, nullable=false)
     */
    private $delegation;

    /**
     * @var string
     *
     * @ORM\Column(name="localite", type="string", length=255, nullable=false)
     */
    private $localite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGovernorat(): ?string
    {
        return $this->governorat;
    }

    public function setGovernorat(string $governorat): self
    {
        $this->governorat = $governorat;

        return $this;
    }

    public function getDelegation(): ?string
    {
        return $this->delegation;
    }

    public function setDelegation(string $delegation): self
    {
        $this->delegation = $delegation;

        return $this;
    }

    public function getLocalite(): ?string
    {
        return $this->localite;
    }

    public function setLocalite(string $localite): self
    {
        $this->localite = $localite;

        return $this;
    }


}

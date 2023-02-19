<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneDeCommande
 *
 * @ORM\Table(name="ligne_de_commande", indexes={@ORM\Index(name="IDX_7982ACE6AABEFE2C", columns={"id_produit_id"}), @ORM\Index(name="IDX_7982ACE69AF8E3A3", columns={"id_commande_id"})})
 * @ORM\Entity
 */
class LigneDeCommande
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
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_produit_id", referencedColumnName="id")
     * })
     */
    private $idProduit;

    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commande_id", referencedColumnName="id")
     * })
     */
    private $idCommande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $idProduit): self
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    public function getIdCommande(): ?Commande
    {
        return $this->idCommande;
    }

    public function setIdCommande(?Commande $idCommande): self
    {
        $this->idCommande = $idCommande;

        return $this;
    }


}

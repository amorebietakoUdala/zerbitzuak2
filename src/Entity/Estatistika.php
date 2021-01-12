<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstatistikaRepository", readOnly=true)
 * @ORM\Table(name="view_estatistikak")
 */
class Estatistika
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne (targetEntity="Enpresa")
     * @ORM\JoinColumn(nullable=false);
     * @JMS\MaxDepth(1)
     * @JMS\Expose()
     */
    private $enpresa;

    /**
     * @ORM\Id
     * @ORM\Column(type="date", nullable=false)
     */
    private $data;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     */
    private $urtea;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     */
    private $hilabetea;

    /**
     * @ORM\Column(type="integer", nullable=false , options={"default":0})
     */
    private $eskakizunak;

    private function __construct()
    {
    }

    public function getId()
    {
        return $this->enpresa->getId().$this->urtea;
    }

    public function getEnpresa()
    {
        return $this->enpresa;
    }

    public function getUrtea()
    {
        return $this->urtea;
    }

    public function getEskakizunak()
    {
        return $this->eskakizunak;
    }

    public function setEnpresa(Enpresa $enpresa)
    {
        $this->enpresa = $enpresa;
    }

    public function setUrtea($urtea)
    {
        $this->urtea = $urtea;
    }

    public function setEskakizunak($eskakizunak)
    {
        $this->eskakizunak = $eskakizunak;
    }

    public function getHilabetea()
    {
        return $this->hilabetea;
    }

    public function setHilabetea($hilabetea)
    {
        $this->hilabetea = $hilabetea;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return $this->getUrtea().''.$this->getHilabetea();
    }
}

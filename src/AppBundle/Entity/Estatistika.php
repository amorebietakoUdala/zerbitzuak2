<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Enpresa;
use JMS\Serializer\Annotation as JMS;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\EstatistikaRepository")
* @ORM\Table(name="estatistikak")
*/

class Estatistika {

    /**
    * @ORM\Id
    * @ORM\ManyToOne (targetEntity="Enpresa")
    * @ORM\JoinColumn(nullable=false);
    * @JMS\MaxDepth(1)
    * @JMS\Expose(true)
    */
    private $enpresa;

    /**
    * @ORM\Id
    * @ORM\Column(type="integer", nullable=false)
    */
    private $urtea;

    /**
    * @ORM\Column(type="integer", nullable=false , options={"default":0})
    */
    private $eskakizunak;
    
    public function getId() {
	return $this->enpresa->getId().$this->urtea;
    }

    public function getEnpresa() {
	return $this->enpresa;
    }

    public function getUrtea() {
	return $this->urtea;
    }

    public function getEskakizunak() {
	return $this->eskakizunak;
    }

    public function setEnpresa(Enpresa $enpresa) {
	$this->enpresa = $enpresa;
    }

    public function setUrtea($urtea) {
	$this->urtea = $urtea;
    }

    public function setEskakizunak($eskakizunak) {
	$this->eskakizunak = $eskakizunak;
    }

    public function __toString() {
	return $this->getUrtea().'';
    }
}

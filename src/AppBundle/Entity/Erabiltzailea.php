<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Arduradunen Entitatea
 *
 * @author ibilbao
 */

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Enpresa;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\ErabiltzaileaRepository")
* @ORM\Table(name="erabiltzaileak")
*/
class Erabiltzailea extends BaseUser {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $izena;

    /**
    * @ORM\ManyToOne (targetEntity="Enpresa", inversedBy="erabiltzaileak")
    * @ORM\JoinColumn(nullable=true);
    */
    private $enpresa;

    /**
    * @ORM\Column(type="string", nullable=true)
    * @JMS\Exclude()
    */
    private $telefonoa;
    /**
    * @ORM\Column(type="string", nullable=true)
    * @JMS\Exclude()
    */
    private $telefonoa2;

    /**
    * @Assert\GreaterThan(0)
    * @ORM\Column(type="integer", nullable=true)
    * @JMS\Exclude()
    */
    private $ordena;

    public function getId() {
	return $this->id;
    }

    public function getIzena() {
        return $this->izena;
    }

    public function setIzena($izena) {
        $this->izena = $izena;
    }

    public function getEnpresa() {
	return $this->enpresa;
    }

    public function getTelefonoa() {
	return $this->telefonoa;
    }

    public function getTelefonoa2() {
	return $this->telefonoa2;
    }

    public function setEnpresa(Enpresa $enpresa = null) {
	$this->enpresa = $enpresa;
    }

    public function setTelefonoa($telefonoa) {
	$this->telefonoa = $telefonoa;
    }

    public function setTelefonoa2($telefonoa2) {
	$this->telefonoa2 = $telefonoa2;
    }

    public function getOrdena() {
	return $this->ordena;
    }

    public function setOrdena($ordena) {
	$this->ordena = $ordena;
    }
    
    public function __toString() {
	return $this->getIzena();
    }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Georeferentziazioa Entitatea
 *
 * @author ibilbao
 */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\GeoreferentziazioaRepository")
* @ORM\Table(name="georeferentziak")
*/
class Georeferentziazioa {
    
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    private $id;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $longitudea;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $latitudea;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $googleAddress;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $mapaLongitudea;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $mapaLatitudea;

    public function getId() {
	return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getGoogleAddress() {
	return $this->googleAddress;
    }

    public function getMapaLongitudea() {
	return $this->mapaLongitudea;
    }

    public function getMapaLatitudea() {
	return $this->mapaLatitudea;
    }

    public function setGoogleAddress($googleAddress) {
	$this->googleAddress = $googleAddress;
    }

    public function setMapaLongitudea($mapaLongitudea) {
	$this->mapaLongitudea = $mapaLongitudea;
    }

    public function setMapaLatitudea($mapaLatitudea) {
	$this->mapaLatitudea = $mapaLatitudea;
    }

    public function getEskakizuna() {
	return $this->eskakizuna;
    }

    public function setEskakizuna(Eskakizuna $eskakizuna) {
	$this->eskakizuna = $eskakizuna;
    }
    public function getLongitudea() {
	return $this->longitudea;
    }

    public function getLatitudea() {
	return $this->latitudea;
    }

    public function setLongitudea($longitudea) {
	$this->longitudea = $longitudea;
    }

    public function setLatitudea($latitudea) {
	$this->latitudea = $latitudea;
    }

    public function __toString() {
	return strval( $this->getId() );
    }
    
}

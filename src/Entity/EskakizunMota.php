<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EskakizunMota Entitatea
 *
 * @author ibilbao
 */
/**
* @ORM\Entity(repositoryClass="App\Repository\EskakizunMotaRepository")
* @ORM\Table(name="eskakizunMotak")
* 
*/

class EskakizunMota {
        /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    private $id;

    /**
    * @ORM\Column(type="string")
    * @Assert\NotBlank()
    */
    private $deskripzioa_es;

    /**
    * @ORM\Column(type="string")
    * @Assert\NotBlank()
    */
    private $deskripzioa_eu;

    public function getId() {
	return $this->id;
    }

    public function getDeskripzioa_es() {
	return $this->deskripzioa_es;
    }

    public function getDeskripzioa_eu() {
	return $this->deskripzioa_eu;
    }

    public function setDeskripzioa_es($deskripzioa_es) {
	$this->deskripzioa_es = $deskripzioa_es;
    }

    public function setDeskripzioa_eu($deskripzioa_eu) {
	$this->deskripzioa_eu = $deskripzioa_eu;
    }

    public function getDeskripzioaEs() {
	return $this->deskripzioa_es;
    }

    public function getDeskripzioaEu() {
	return $this->deskripzioa_eu;
    }

    public function setDeskripzioaEs($deskripzioa_es) {
	$this->deskripzioa_es = $deskripzioa_es;
    }

    public function setDeskripzioaEu($deskripzioa_eu) {
	$this->deskripzioa_eu = $deskripzioa_eu;
    }

    public function __toString() {
	return $this->getDeskripzioaEu();
    }
}

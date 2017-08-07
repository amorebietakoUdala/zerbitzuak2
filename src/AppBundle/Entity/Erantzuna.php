<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Erantzuna Entitatea
 *
 * @author ibilbao
 */
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Erabiltzailea;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\ErantzunaRepository")
* @ORM\Table(name="erantzunak")
*/

class Erantzuna {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    private $id;

    /**
    * @ORM\Column(type="string")
    */
    private $erantzuna;

    /**
    * @ORM\Column(type="datetime")
    * @Assert\NotBlank()
    */
    private $noiz;

    /**
    * @ORM\ManyToOne(targetEntity="Eskakizuna", inversedBy="erantzunak")
    */
    private $eskakizuna;

    /**
    * @ORM\ManyToOne(targetEntity="Erabiltzailea")
    */
    private $erantzulea;

    public function getId() {
	return $this->id;
    }

    public function getErantzuna() {
	return $this->erantzuna;
    }

    public function getEskakizuna() {
	return $this->eskakizuna;
    }

    public function getErantzulea() {
	return $this->erantzulea;
    }

    public function setId($id) {
	$this->id = $id;
    }

    public function setErantzuna($erantzuna) {
	$this->erantzuna = $erantzuna;
    }

    public function setEskakizuna(Eskakizuna $eskakizuna) {
	$this->eskakizuna = $eskakizuna;
    }

    public function setErantzulea(Erabiltzailea $erantzulea) {
	$this->erantzulea = $erantzulea;
    }

    public function getNoiz() {
	return $this->noiz;
    }

    public function setNoiz($noiz) {
	$this->noiz = $noiz;
    }

    public function __toString() {
	return $this->getErantzuna();
    }
}

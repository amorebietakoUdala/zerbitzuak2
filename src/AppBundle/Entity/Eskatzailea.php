<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Egoeren Eskatzailea
 *
 * @author ibilbao
 */

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\EskatzaileaRepository")
* @ORM\Table(name="eskatzaileak")
*/
class Eskatzailea {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    * @JMS\Expose(true)
    * @JMS\Groups({"ROLE_ADMIN","ROLE_ARDURADUNA","ROLE_INFORMATZAILEA"})
    */
    private $id;

    /**
    * @ORM\Column(type="string")
    * @Assert\NotBlank()
    * @JMS\Expose(true)
    * @JMS\Groups({"ROLE_ADMIN","ROLE_ARDURADUNA","ROLE_INFORMATZAILEA"})
    */
    private $izena;

    /**
    * @ORM\Column(type="string", nullable=true)
    * @JMS\Expose(true)
    * @JMS\Groups({"ROLE_ADMIN","ROLE_ARDURADUNA","ROLE_INFORMATZAILEA"})
    */

    private $telefonoa;
    
    /**
    * @ORM\Column(type="string", nullable=true)
    * @Assert\Regex(pattern="/^\d{7,8}[a-z]$/i", 
    *		    message="NANa ez da zuzena"
    *		    )
    * @JMS\Expose(true)
    * @JMS\Groups({"ROLE_ADMIN","ROLE_ARDURADUNA","ROLE_INFORMATZAILEA"})
    */
    private $nan;
    
    /**
    * @ORM\Column(type="string", nullable=true)
    * @JMS\Expose(true)
    * @JMS\Groups({"ROLE_ADMIN","ROLE_ARDURADUNA","ROLE_INFORMATZAILEA"})
    */
    private $helbidea;

    /**
    * @Assert\Email()
    * @ORM\Column(type="string", nullable=true)
    * @JMS\Expose(true)
    * @JMS\Groups({"ROLE_ADMIN","ROLE_ARDURADUNA","ROLE_INFORMATZAILEA"})
    */
    private $emaila;

    /**
    * @ORM\Column(type="string", nullable=true)
    * @JMS\Expose(true)
    * @JMS\Groups({"ROLE_ADMIN","ROLE_ARDURADUNA","ROLE_INFORMATZAILEA"})
    */
    private $herria;

    /**
    * @ORM\Column(type="string", nullable=true)
    * @Assert\Regex(pattern="/((5[0-2]|[0-4][0-9])[0-9]{3})/",
    *		message="postaKodea ez da zuzena")
    * @JMS\Expose(true)
    * @JMS\Groups({"ROLE_ADMIN","ROLE_ARDURADUNA","ROLE_INFORMATZAILEA"})
    */
    private $postaKodea;
    
    /**
    * @ORM\Column(type="string", nullable=true)
    * @JMS\Expose(true)
    * @JMS\Groups({"ROLE_ADMIN","ROLE_ARDURADUNA","ROLE_INFORMATZAILEA"})
    */
    private $faxa;
    
    /**
    * @ORM\OneToMany(targetEntity="Eskakizuna", mappedBy="eskatzailea")
    * @ORM\JoinColumn(nullable=true)
    * @JMS\Exclude()
    */
    private $eskakizunak;
    
    public function __construct() {
	$this->eskakizunak = new ArrayCollection();
    }
    
    public function getId() {
	return $this->id;
    }

    public function getIzena() {
	return $this->izena;
    }

    public function getTelefonoa() {
	return $this->telefonoa;
    }

    public function getNan() {
	return $this->nan;
    }

    public function getHelbidea() {
	return $this->helbidea;
    }

    public function getEmaila() {
	return $this->emaila;
    }

    public function getHerria() {
	return $this->herria;
    }

    public function getPostaKodea() {
	return $this->postaKodea;
    }

    public function getPosta_kodea() {
	return $this->postaKodea;
    }

    
    public function getFaxa() {
	return $this->faxa;
    }
    
    public function setId($id) {
        $this->id = $id;
    }

    public function setIzena($izena) {
	$this->izena = $izena;
    }

    public function setTelefonoa($telefonoa) {
	$this->telefonoa = $telefonoa;
    }

    public function setNan($nan) {
	$this->nan = $nan;
    }

    public function setHelbidea($helbidea) {
	$this->helbidea = $helbidea;
    }

    public function setEmaila($emaila) {
	$this->emaila = $emaila;
    }

    public function setHerria($herria) {
	$this->herria = $herria;
    }

    public function setPostaKodea($postaKodea) {
	$this->postaKodea = $postaKodea;
    }

    public function setPosta_kodea($postaKodea) {
	$this->postaKodea = $postaKodea;
    }

    public function setFaxa($faxa) {
	$this->faxa = $faxa;
    }

    /**
    * 
    * @return ArrayCollection|Eskakizunak[]
    */
    public function getEskakizunak() {
	return $this->eskakizunak;
    }

    public function __toString() {
	return $this->getIzena();
    }
}

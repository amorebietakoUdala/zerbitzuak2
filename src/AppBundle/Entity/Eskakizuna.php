<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;


/**
 * Arduradunen Eskakizuna
 *
 * @author ibilbao
 */

use AppBundle\Entity\Erantzuna;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\EskakizunaRepository")
* @ORM\Table(name="eskakizunak")
* @JMS\ExclusionPolicy("all")
*/
class Eskakizuna {

    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    * @JMS\Expose(true)
    */
    private $id;

    /**
    * @ORM\ManyToOne (targetEntity="Eskatzailea", inversedBy="eskakizunak")
    * @ORM\JoinColumn(nullable=false);
    * @Assert\NotBlank()
    * @JMS\MaxDepth(1)
    * @JMS\Expose(true)
    */
    private $eskatzailea;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @JMS\Expose(true)
     */
    private $lep;
    
    /**
    * @ORM\ManyToOne (targetEntity="EskakizunMota")
    * @Assert\NotBlank()
    * @JMS\Expose(true)
    */
    private $eskakizunMota;

    /**
    * @ORM\ManyToOne (targetEntity="Jatorria")
    * @Assert\NotBlank()
    * @JMS\Expose(true)
    */
    private $jatorria;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @JMS\Expose(true)
     */
    private $mamia;
 
    /**
     * @ORM\Column(type="string", nullable=true)
     * @JMS\Expose(true)
     */
    private $kalea;

    /**
    * @ORM\ManyToOne (targetEntity="Enpresa", inversedBy="eskakizunak")
    * @ORM\JoinColumn(nullable=true);
    * @JMS\MaxDepth(1)
    * @JMS\Expose(true)
    */
    private $enpresa;
    /**
    * @ORM\ManyToOne (targetEntity="Zerbitzua", inversedBy="eskakizunak")
    * @ORM\JoinColumn(nullable=true);
    * @JMS\MaxDepth(1)
    * @JMS\Expose(true)
    */
    private $zerbitzua;
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @JMS\Expose(true)     
    */
    private $noiz;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    * @JMS\Expose(true)     
    */
    private $noizInformatua;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    * @JMS\Expose(true)     
    */
    private $noizBidalia;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    * @JMS\Expose(true)     
    */
    private $noizErreklamatua;

    /**
    * @ORM\ManyToOne (targetEntity="Erabiltzailea")
    * @ORM\JoinColumn(nullable=true);
    * @JMS\MaxDepth(1)
    * @JMS\Expose(true)
    */
    private $norkErreklamatua;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Expose(true)     
     */
    private $itxieraData;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @JMS\Expose(true)     
     */
    private $argazkia;

    /**
    * Eskakizun askok egoera bat daukate (OWNING SIDE)
    * @ORM\ManyToOne (targetEntity="Egoera")
    * @ORM\JoinColumn(nullable=true);
    * @JMS\MaxDepth(1)
    * @JMS\Expose(true)
    */
    private $egoera;

    /**
    * Eskakizun askok georeferentziazio bat daukate (OWNING SIDE)
    * @ORM\ManyToOne (targetEntity="Georeferentziazioa", cascade={"persist", "remove"} )
    * @ORM\JoinColumn(nullable=true);
    * @JMS\MaxDepth(1)
    * @JMS\Expose(true)
    */
    private $georeferentziazioa;

    /**
    * @ORM\OneToMany (targetEntity="Erantzuna", mappedBy="eskakizuna", cascade={"persist", "remove"})
    * @ORM\JoinColumn(nullable=true);
    */
    private $erantzunak;

    /**
    * Eskakizun askok informatzailea bat daukate
    * @ORM\ManyToOne (targetEntity="Erabiltzailea")
    * @ORM\JoinColumn(nullable=true);
    */
    private $norkInformatua;

    public function __construct() {
	$this->erantzunak = new ArrayCollection();
    }

    public function getId() {
	return $this->id;
    }

    public function getEskatzailea() {
	return $this->eskatzailea;
    }

    public function getEskakizunMota() {
	return $this->eskakizunMota;
    }

    public function getJatorria() {
	return $this->jatorria;
    }

    public function getMamia() {
	return $this->mamia;
    }

    public function getKalea() {
	return $this->kalea;
    }

    public function getZerbitzua() {
	return $this->zerbitzua;
    }

    public function getNoiz() {
	return $this->noiz;
    }

    public function getNoizBidalia() {
	return $this->noizBidalia;
    }

    public function getItxieraData() {
	return $this->itxieraData;
    }

    public function getEgoera() {
	return $this->egoera;
    }

    /**
    * 
    * @return ArrayCollection|Erantzunak[]
    */
    public function getErantzunak() {
	return $this->erantzunak;
    }
    
    public function addErantzunak(Erantzuna $erantzuna)
    {
        $erantzuna->setNoiz(new \DateTime());
        $erantzuna->setEskakizuna($this);
        $this->erantzunak->add($erantzuna);
    }

    public function removeErantzunak(Erantzuna $erantzuna)
    {
        $this->erantzunak->removeElement($erantzuna);
    }

    public function getNorkInformatua() {
	return $this->norkInformatua;
    }
    
    public function getLep() {
        return $this->lep;
    }

    public function setEskatzailea(Eskatzailea $eskatzailea) {
	$this->eskatzailea = $eskatzailea;
    }

    public function setEskakizunMota(EskakizunMota $eskakizunMota) {
	$this->eskakizunMota = $eskakizunMota;
    }

    public function setJatorria($jatorria) {
	$this->jatorria = $jatorria;
    }

    public function setMamia($mamia) {
	$this->mamia = $mamia;
    }

    public function setKalea($kalea) {
	$this->kalea = $kalea;
    }

    public function setZerbitzua(Zerbitzua $zerbitzua = null ) {
	$this->zerbitzua = $zerbitzua;
    }

    public function setNoiz($noiz) {
	$this->noiz = $noiz;
    }

    public function setNoizBidalia($noizBidalia) {
	$this->noizBidalia = $noizBidalia;
    }

    public function setItxieraData($itxieraData) {
	$this->itxieraData = $itxieraData;
    }

    public function setEgoera(Egoera $egoera = null) {
	$this->egoera = $egoera;
    }

    public function setNorkInformatua(Erabiltzailea $norkInformatua) {
	$this->norkInformatua = $norkInformatua;
    }

    public function getArgazkia() {
	return $this->argazkia;
    }

    public function setArgazkia($argazkia) {
	$this->argazkia = $argazkia;
    }

    public function setLep($lep) {
        $this->lep = $lep;
    }
    public function getEnpresa() {
        return $this->enpresa;
    }

    public function setEnpresa(Enpresa $enpresa) {
        $this->enpresa = $enpresa;
    }

    public function getGeoreferentziazioa() {
	return $this->georeferentziazioa;
    }
    
    /* Ez badator georeferentziazioa edo kentzen badiogu null jartzen dio egiten du */
    public function setGeoreferentziazioa(Georeferentziazioa $georeferentziazioa = null) {
	$this->georeferentziazioa = $georeferentziazioa;
    }
    public function getNoizErreklamatua() {
	return $this->noizErreklamatua;
    }

    public function getNorkErreklamatua() {
	return $this->norkErreklamatua;
    }

    /* @return Erabiltzailea */
    public function setNoizErreklamatua($noizErreklamatua) {
	$this->noizErreklamatua = $noizErreklamatua;
    }

    public function setNorkErreklamatua(Erabiltzailea $norkErreklamatua) {
	$this->norkErreklamatua = $norkErreklamatua;
    }
    
    public function getNoizInformatua() {
	return $this->noizInformatua;
    }

    public function setNoizInformatua($noizInformatua) {
	$this->noizInformatua = $noizInformatua;
    }

        public function __toString() {
	return strval($this->getId());
    }
}

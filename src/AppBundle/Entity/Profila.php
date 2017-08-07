<?php

namespace AppBundle\Entity;

/**
 * Arduradunen Entitatea
 *
 * @author ibilbao
 */

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\ProfilaRepository")
* @ORM\Table(name="profilak")
*/
class Profila {
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
    private $izena;

    /**
    * @Assert\GreaterThan(0)
    * @ORM\Column(type="integer", nullable=true)
    */
    private $ordena;

    public function getId() {
        return $this->id;
    }

    public function getIzena() {
        return $this->izena;
    }

    public function getOrdena() {
        return $this->ordena;
    }

    public function setIzena($izena) {
        $this->izena = $izena;
    }

    public function setOrdena($ordena) {
        $this->ordena = $ordena;
    }

    public function __toString() {
	return $this->getIzena();
    }

}

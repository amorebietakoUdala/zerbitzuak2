<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\User;

use AppBundle\Controller\Web\User\EskakizunaFormType;
use AppBundle\Entity\Egoera;
use AppBundle\Entity\Enpresa;
use AppBundle\Entity\Erabiltzailea;
use AppBundle\Entity\Eskakizuna;
use AppBundle\Entity\Eskatzailea;
use AppBundle\Entity\Georeferentziazioa;
use AppBundle\Controller\Web\User\EskakizunaBilatzaileaFormType;
use Imagick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use \Swift_Message;


/**
 * Description of EskakizunaController
 *
 * @author ibilbao

/**
* @Route("/{_locale}/eskakizuna")
*/
class EskakizunaController extends Controller {
    private $eskatzailea;
    private $eskakizuna;
    private $georeferentziazioa;
    /**
     * @Route("/new", name="admin_eskakizuna_new", options={"expose" = true})
     */
    public function newAction (Request $request){
	$user = $this->get('security.token_storage')->getToken()->getUser();
	$form = $this->createForm(EskakizunaFormType::class, new Eskakizuna(), [
	    'editatzen' => false,
	    'role' => $user->getRoles(),
	]);
	
	// Only handles data on POST request
	$form->handleRequest($request);
	if ( $form->isSubmitted() && $form->isValid() ) {
	    $em = $this->getDoctrine()->getManager();

	    /* @var data \AppBundle\Entity\Eskakizuna */
            $eskakizuna = $form->getData();
            $this->eskatzailea = $eskakizuna->getEskatzailea();
	    if ( $this->eskatzailea->getId() !== null ) {
                $this->eskatzailea = $em->getRepository(Eskatzailea::class)->find($this->eskatzailea->getId());
            } else {
                $this->eskatzailea = new Eskatzailea();
            }
            
            $this->_parseEskatzailea($form);
	    $this->eskakizuna = new Eskakizuna();
	    $this->_parseEskakizuna($form);
	    $this->eskakizuna->setEskatzailea($this->eskatzailea);
	    $georeferentziazioa = $eskakizuna->getGeoreferentziazioa();
	    if ( $georeferentziazioa->getLongitudea() !== null && $georeferentziazioa->getLatitudea() !== null ) {
		$this->eskakizuna->setGeoreferentziazioa($georeferentziazioa);
		$em->persist($georeferentziazioa);
	    }
	    $zerbitzua = $this->eskakizuna->getZerbitzua();
	    $zerbitzua_hautatua = false;
	    if ($zerbitzua !== null) {
		$this->eskakizuna->setEnpresa($zerbitzua->getEnpresa());
		$zerbitzua_hautatua = true;
	    }
	    $this->_argazkia_gorde();
            if ( $eskakizuna->getZerbitzua() != null ) {
                $egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_BIDALIA);
                $this->eskakizuna->setEgoera($egoera);
		$this->eskakizuna->setNoizBidalia(new \DateTime());
            } else {
                $egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_BIDALI_GABE);
                $this->eskakizuna->setEgoera($egoera);
            }

	    $this->eskakizuna->setNorkInformatua($user);
	    
	    $em->persist($this->eskatzailea);
	    $em->persist($this->eskakizuna);
	    $em->flush();
	    $mezuak_bidali = $this->getParameter('mezuak_bidali');
	    if ( $mezuak_bidali && $zerbitzua_hautatua ) {
		$title = 'Eskakizun Berria. Eskakizun zenbakia:';
		$this->_mezuaBidaliEnpresari($title, $this->eskakizuna, $this->eskakizuna->getEnpresa());
	    }

	    $this->addFlash('success', 'messages.eskakizuna_gordea');
	    return $this->redirectToRoute('admin_eskakizuna_new');
	}
	
	return $this->render('/eskakizuna/new.html.twig', [
	    'eskakizunaForm' => $form->createView(),
	    'argazkia' => null,
	    'erantzun' => false,
	    'editatzen' => false
	]);
    }

     /**
     * @Route("/", name="admin_eskakizuna_list", options={"expose" = true})
     */
    public function listAction (Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
	$authorization_checker = $this->get('security.authorization_checker');
	$bilatzaileaForm = $this->createForm(EskakizunaBilatzaileaFormType::class,[
	    'role' => $user->getRoles(),
	    'enpresa' => $user->getEnpresa()
	]);
	
	$bilatzaileaForm->handleRequest($request);
	if ( $bilatzaileaForm->isSubmitted() && $bilatzaileaForm->isValid() ) {
	    $criteria = $bilatzaileaForm->getData();
	    $criteria['role'] = null; 
	    if ( $authorization_checker->isGranted('ROLE_KANPOKO_TEKNIKARIA') ) {
		$criteria['enpresa'] = $user->getEnpresa(); 
	    }
	    $from = array_key_exists( 'noiztik', $criteria ) ? $criteria['noiztik'] : null;
	    $to = array_key_exists( 'nora', $criteria ) ? $criteria['nora'] : null;
	    $criteria_without_blanks = $this->_remove_noiztik_nora($criteria);

	    
	    if ( array_key_exists('egoera', $criteria_without_blanks) ) {
		$eskakizunak = $this->getDoctrine()
		    ->getRepository(Eskakizuna::class)
		    ->findAllFromTo($criteria_without_blanks,$from,$to);
	    } else { 
		$eskakizunak = $this->getDoctrine()
		    ->getRepository(Eskakizuna::class)
		    ->findAllOpen($criteria_without_blanks,$from,$to);
	    }
	    return $this->render('/eskakizuna/list.html.twig', [
		'bilatzaileaForm' => $bilatzaileaForm->createView(),
		'eskakizunak' => $eskakizunak,
	    ]);
	}
	
	$em = $this->getDoctrine()->getManager();
	if ( $authorization_checker->isGranted('ROLE_KANPOKO_TEKNIKARIA') ) {
	    $eskakizunak = $em->getRepository(Eskakizuna::class)->findBy([
		    'enpresa' => $user->getEnpresa()
		    ]
	    );
	} else {
	    $criteria = $request->query->all();
	    $from = array_key_exists( 'noiztik', $criteria ) ? $criteria['noiztik'] : null;
	    $to = array_key_exists( 'nora', $criteria ) ? $criteria['nora'] : null;
	    $criteria_without_blanks = $this->_remove_noiztik_nora($criteria);
	    $eskakizunak = $this->getDoctrine()
		    ->getRepository(Eskakizuna::class)
		    ->findAllOpen($criteria_without_blanks, $from, $to);
	    }

	return $this->render('/eskakizuna/list.html.twig', [
	    'bilatzaileaForm' => $bilatzaileaForm->createView(),
	    'eskakizunak' => $eskakizunak,
	]);
    }

    /**
     * @Route("/{id}/edit", name="admin_eskakizuna_edit")
     */
    public function editAction (Request $request, Eskakizuna $eskakizuna){
	$logger = $this->get('logger');
        $user = $this->get('security.token_storage')->getToken()->getUser();
	$form = $this->createForm(EskakizunaFormType::class, $eskakizuna, [
	    'editatzen' => true,
   	    'role' => $user->getRoles(),
	]);

	$erantzunakAldatuBarik = new ArrayCollection();
	$zerbitzuaAldatuAurretik = $eskakizuna->getZerbitzua();
	
	foreach ($eskakizuna->getErantzunak() as $erantzuna) {
	    $erantzunakAldatuBarik->add($erantzuna);
	}

	$form->handleRequest($request);
	if ( $form->isSubmitted() && $form->isValid() ) {
	    $em = $this->getDoctrine()->getManager();
	    $this->eskakizuna = $form->getData();
	    $geo = $this->eskakizuna->getGeoreferentziazioa();

	    if ( $geo !== null && $geo->getId() !== null ) {
		$geo = $em->getRepository(Georeferentziazioa::class)->find($eskakizuna->getId());
	    } else if ($geo->getLongitudea() !== null && $geo->getLatitudea() !== null ) {
		    $this->georeferentziazioa = $geo;
		    $this->eskakizuna->setGeoreferentziazioa($this->georeferentziazioa);
		    $em->persist($this->georeferentziazioa);
	    }

	    $logger->debug('Zerbitzua: '.$eskakizuna->getZerbitzua());
	    if ( $this->eskakizuna->getZerbitzua() !== null ) 
	    {
		$zerbitzua = $this->eskakizuna->getZerbitzua();
		$logger->debug('Zerbitzua aldatu aurretik: '.$zerbitzuaAldatuAurretik->getIzena_eu());
		$logger->debug('Zerbitzua aldatu ostean: '.$zerbitzua->getIzena_eu());
		$this->eskakizuna->setEnpresa($zerbitzua->getEnpresa());
		if ( $this->eskakizuna->getEgoera()->getId() === Egoera::EGOERA_BIDALI_GABE || $zerbitzua->getId() !== $zerbitzuaAldatuAurretik->getId()) {
		    $logger->debug('Egoera: Bidali gabe edo zerbitzua aldatua');
		    $egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_BIDALIA);
		    $this->eskakizuna->setEgoera($egoera);
		    $this->eskakizuna->setNoizBidalia(new \DateTime());
		    $title = 'Eskakizuna esleitu egin zaizu. Eskakizun zenbakia:';
		    $mezuak_bidali = $this->getParameter('mezuak_bidali');
		    if ($mezuak_bidali) {
			$this->_mezuaBidaliEnpresari($title, $this->eskakizuna, $this->eskakizuna->getEnpresa());
			$logger->debug('Mezua Bidalia');
		    }
		}
	    }
	    $this->_argazkia_gorde();
	    
	    foreach ($erantzunakAldatuBarik as $erantzuna) {
		if (false === $eskakizuna->getErantzunak()->contains($erantzuna)) {
		    $em->remove($erantzuna);
		}
	    }
	    
            $erantzunak = $this->eskakizuna->getErantzunak();
            foreach ( $erantzunak as $erantzuna ) {
                if ($erantzuna->getErantzulea() == null ) {
                    $erantzuna->setErantzulea($user);
		    $erantzuna->setNoiz(new \DateTime());
                    $em->persist($erantzuna);
                }
            }
	    if ($erantzunak->count() > 0) {
		$egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_ERANTZUNDA);
		$this->eskakizuna->setEgoera($egoera);
	    }
	    $em->persist($this->eskakizuna);
	    $em->flush();
	    
	    $this->addFlash('success', 'messages.eskakizuna_gordea');
	    
	    return $this->redirectToRoute('admin_eskakizuna_list');
	}
	
	return $this->render('/eskakizuna/edit.html.twig', [
	    'eskakizunaForm' => $form->createView(),
	    'editatzen' => true,
	    'erantzun' => false
	]);
		
    }
    /**
     * @Route("/{id}/delete", name="admin_eskakizuna_delete")
     */
    public function deleteAction (Request $request, $id){
	$em = $this->getDoctrine()->getManager();
	$eskakizuna = $em->getRepository(Eskakizuna::class)->findOneBy([
	    'id' => $id 
	]);

	if (!$eskakizuna) {
	    $this->addFlash('error', 'messages.eskakizuna_ez_da_existitzen');
	    return $this->listAction();
	}
	
	$em->remove($eskakizuna);
	$em->flush();
	
	$this->addFlash('success', 'messages.eskakizuna_ezabatua');
	
	return $this->redirectToRoute('admin_eskakizuna_list');
		
    }

    /**
     * @Route("/{id}", name="admin_eskakizuna_show")
     */

    public function showAction (Request $request, Eskakizuna $eskakizuna){
	$user = $this->get('security.token_storage')->getToken()->getUser();
	$this->get('logger')->debug('Show. Eskakizun zenbakia: '.$eskakizuna->getId());
	$argazkien_direktorioa = $this->getParameter('images_uploads_url');

	$eskakizunaForm = $this->createForm(EskakizunaFormType::class, $eskakizuna, [
	    'editatzen' => false,
	    'readonly' => true,
	    'role' => $user->getRoles(),
	    ]);

	$eskakizunaForm->handleRequest($request);
	if ( $eskakizunaForm->isSubmitted() && $eskakizunaForm->isValid() ) {
	    $em = $this->getDoctrine()->getManager();
	    $this->eskakizuna = $eskakizunaForm->getData();
            $erantzunak = $this->eskakizuna->getErantzunak();
            foreach ( $erantzunak as $erantzuna ) {
                if ($erantzuna->getErantzulea() == null ) {
                    $erantzuna->setErantzulea($user);
                    $em->persist($erantzuna);
                }
            }
	    
	    if ($this->eskakizuna->getArgazkia() === null) {
		$this->eskakizuna->setArgazkia($eskakizuna->getArgazkia());
	    }

	    // Zerbitzurik ez badauka ez dugu emailik bidaltzen.
	    if ($erantzunak->count() > 0 && $this->eskakizuna->getZerbitzua() !== null) {
		$egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_ERANTZUNDA);
		$this->eskakizuna->setEgoera($egoera);
		$erantzundakoan_mezua_bidali = $this->getParameter('erantzundakoan_mezua_bidali');
		$mezuak_bidali = $this->getParameter('mezuak_bidali');
		if ( $erantzundakoan_mezua_bidali && $mezuak_bidali ) {
		    $title = 'Eskakizuna erantzunda. Eskakizun zenbakia: ';
		    $this->_mezuaBidaliArduradunei($title, $this->eskakizuna);
		}
	    }
	    $em->persist($this->eskakizuna);
	    $em->flush();

	    $this->addFlash('success', 'messages.erantzuna_gordea');
	    return $this->render('/eskakizuna/show.html.twig', [
		'eskakizunaForm' => $eskakizunaForm->createView(),
		'argazkia' => $argazkien_direktorioa.'/'.$eskakizuna->getArgazkia(),
		'editatzen' => false,
		'erantzun' => true
	    ]);
	}

	return $this->render('/eskakizuna/show.html.twig', [
	    'eskakizunaForm' => $eskakizunaForm->createView(),
	    'argazkia' => $argazkien_direktorioa.'/'.$eskakizuna->getArgazkia(),
	    'editatzen' => false,
	    'erantzun' => true
	]);
    }

     /**
     * @Route("/{id}/close", name="admin_eskakizuna_close")
     */
    public function closeAction (Request $request, Eskakizuna $eskakizuna){
	if (!$eskakizuna) {
	    $this->addFlash('error', 'messages.eskakizuna_ez_da_existitzen');
	    return $this->listAction();
	}
	
	$em = $this->getDoctrine()->getManager();
	$eskakizuna->setItxieraData(new \DateTime());
	$egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_ITXIA);
	$eskakizuna->setEgoera($egoera);

	$em->persist($eskakizuna);
	$em->flush();
	
	$this->addFlash('success', 'messages.eskakizuna_itxia');
	
	return $this->redirectToRoute('admin_eskakizuna_list');
		
    }

     /**
     * @Route("/{id}/resend", name="admin_eskakizuna_resend")
     */
    public function resendAction (Request $request, Eskakizuna $eskakizuna){
	$user = $this->get('security.token_storage')->getToken()->getUser();
	if (!$eskakizuna) {
	    $this->addFlash('error', 'messages.eskakizuna_ez_da_existitzen');
	    return $this->listAction();
	}
	
	$em = $this->getDoctrine()->getManager();
	$eskakizuna->setNoizErreklamatua(new \DateTime());
	$eskakizuna->setNorkErreklamatua($user);
	$title = 'Eskakizuna erreklamatua. Eskakizun zenbakia: ';
	$this->_mezuaBidaliEnpresari ($title, $eskakizuna, $eskakizuna->getEnpresa());
	
	$em->persist($eskakizuna);
	$em->flush();
	
	$this->addFlash('success', 'messages.eskakizuna_erreklamatua');
	
	return $this->redirectToRoute('admin_eskakizuna_list');
		
    }

    private function _parseEskatzailea($form){
        $data = $form->getData();
	$eskatzailea = $data->getEskatzailea();
	$this->eskatzailea->setIzena($eskatzailea->getIzena());
	$this->eskatzailea->setNan($eskatzailea->getNan());
	$this->eskatzailea->setTelefonoa($eskatzailea->getTelefonoa());
	$this->eskatzailea->setFaxa($eskatzailea->getFaxa());
	$this->eskatzailea->setHelbidea($eskatzailea->getHelbidea());
	$this->eskatzailea->setEmaila($eskatzailea->getEmaila());
	$this->eskatzailea->setHerria($eskatzailea->getHerria());
	$this->eskatzailea->setPostaKodea($eskatzailea->getPostaKodea());
    }
    
    private function _parseEskakizuna($form){
	$data = $form->getData();
	$this->eskakizuna->setLep($data->getLep());
	$this->eskakizuna->setNoiz($data->getNoiz());
	$this->eskakizuna->setKalea($data->getKalea());
	$this->eskakizuna->setArgazkia($data->getArgazkia());
	$this->eskakizuna->setEskakizunMota($data->getEskakizunMota());
	$this->eskakizuna->setJatorria($data->getJatorria());
	$this->eskakizuna->setMamia($data->getMamia());
	if ( $data->getZerbitzua() !== null ) {
	    $this->eskakizuna->setZerbitzua($data->getZerbitzua());
	}
    }

    private function _argazkia_gorde() {
	$argazkia = $this->eskakizuna->getArgazkia();
	if ( $argazkia !== null ) {
	    // Generate a unique name for the file before saving it
	    $argazkiaren_izena = md5(uniqid()).'.'.$argazkia->guessExtension();

	    // Move the file to the directory where brochures are stored
	    $argazkien_direktorioa = $this->getParameter('images_uploads_directory');
	    $argazkien_zabalera = $this->getParameter('images_width');
	    $argazkien_thumb_zabalera = $this->getParameter('images_thumb_width');

	    $argazkia->move(
		$argazkien_direktorioa,
		$argazkiaren_izena
	    );
	    /* Honek funtzionatzen du baina agian zuzenean txikituta gorde daiteke */
	    $image = new \Imagick($argazkien_direktorioa.'/'.$argazkiaren_izena);
	    $image->thumbnailImage($argazkien_zabalera,0);
	    $image->writeImage($argazkien_direktorioa.'/'.$argazkiaren_izena);
	    $image->thumbnailImage($argazkien_thumb_zabalera,0);
	    $image->writeImage($argazkien_direktorioa.'/'.'thumb-'.$argazkiaren_izena);

	    $this->eskakizuna->setArgazkia($argazkiaren_izena);
	}
    }

    private function _mezuaBidaliArduradunei ($title, $eskakizuna) {
	$em = $this->getDoctrine()->getManager();
	$jasotzaileak = $em->getRepository(Erabiltzailea::class)->findByRole('ROLE_ARDURADUNA');
	$emailak = [];
	foreach ($jasotzaileak as $jasotzailea) {
	    $emailak [] = $jasotzailea->getEmail(); 
	}
	$this->_mezuaBidali ($title, $this->eskakizuna, $emailak);
    }

    private function _mezuaBidaliEnpresari ($title, $eskakizuna, Enpresa $enpresa) {
	$em = $this->getDoctrine()->getManager();
	$jasotzaileak = $em->getRepository(Erabiltzailea::class)->findBy( [
	    "enpresa" => $enpresa,
	    "enabled" => true
	]);
	$emailak = [];
	foreach ($jasotzaileak as $jasotzailea) {
	    $emailak [] = $jasotzailea->getEmail(); 
	}
	 $this->_mezuaBidali ($title, $eskakizuna, $emailak);
    }
    
    private function _mezuaBidali ($title, $eskakizuna, $emailak) {
	$from = $this->getParameter('mailer_from');
	$mailer = $this->get('mailer');
	$message = new Swift_Message($title.' '.$eskakizuna->getId());
	$message->setFrom($from);
//	$message->setTo('ibilbao@amorebieta.eus');
	// TODO deskomentatu denei bidaltzeko
//	$message->setTo($this->getParameter('mailer_from'));
	$message->setTo($emailak);
	$message->setBody(
	    $this->renderView('/eskakizuna/mail.html.twig', [
		    'eskakizuna' => $eskakizuna
		])
	);
	$message->setContentType('text/html');

	$mailer->send($message);
    }

    private function _remove_noiztik_nora($criteria) {
	$from = null;
	$to = null;
	if ( array_key_exists( 'noiztik', $criteria )) {
	    $from = $criteria['noiztik'];
	    $criteria['noiztik']  = null;
	}
	if ( array_key_exists( 'nora', $criteria )) {
	    $to = $criteria['nora'];
	    $criteria['nora']  = null;
	}
	$new_criteria = $this->_remove_blank_filters($criteria);
	return $new_criteria;
    }

    private function _remove_blank_filters ($criteria) {
	$new_criteria = [];
	foreach ($criteria as $key => $value) {
	    if (!empty($value))
		$new_criteria[$key] = $value;
	}
	return $new_criteria;
    }
}

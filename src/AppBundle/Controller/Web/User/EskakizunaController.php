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
use AppBundle\Entity\Erantzuna;
use AppBundle\Entity\Georeferentziazioa;
use AppBundle\Entity\Eranskina;
use AppBundle\Entity\Zerbitzua;
use AppBundle\Entity\Argazkia;
use AppBundle\Controller\Web\User\EskakizunaBilatzaileaFormType;
use Imagick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use \Swift_Message;
use \DateTime;


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
    	    'locale' => $request->getLocale(),
	]);
	$returnPage = $this->_getReturnPage($request);
	// Only handles data on POST request
	$form->handleRequest($request);
	if ( $form->isSubmitted() && $form->isValid() ) {
	    $em = $this->getDoctrine()->getManager();

	    /* @var data \AppBundle\Entity\Eskakizuna */
//	    dump($form->getData());die;
            $this->eskakizuna = $form->getData();
            $this->eskatzailea = $this->eskakizuna->getEskatzailea();
	    if ( $this->eskatzailea->getId() !== null ) {
                $this->eskatzailea = $em->getRepository(Eskatzailea::class)->find($this->eskatzailea->getId());
            } else {
                $this->eskatzailea = new Eskatzailea();
            }
	    
            $this->_parseEskatzailea($form);
	    $this->eskakizuna->setEskatzailea($this->eskatzailea);
	    $georeferentziazioa = $form->getData()->getGeoreferentziazioa();
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
//	    dump($this->eskakizuna);die;
	    $this->_argazkia_gorde_multi();
	    $this->_eranskinak_gorde_multi();
            if ( $this->eskakizuna->getZerbitzua() != null ) {
                $egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_BIDALIA);
                $this->eskakizuna->setEgoera($egoera);
		$this->eskakizuna->setNoizBidalia(new \DateTime());
            } else {
                $egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_BIDALI_GABE);
                $this->eskakizuna->setEgoera($egoera);
            }

	    $this->eskakizuna->setNorkInformatua($user);
	    $this->eskakizuna->setNoizInformatua(new \DateTime());
	    
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
// 'erantzunak' => [],
	    'erantzun' => false,
	    'editatzen' => false,
	    'returnPage' => $returnPage,
	]);
    }

     /**
     * @Route("/atzotik", name="admin_eskakizuna_atzotik", options={"expose" = true})
     */
    public function listAtzotikAction (Request $request){
	$gaur = new \DateTime();
	return $this->redirectToRoute('admin_eskakizuna_list',[
	    'nora' => $gaur->format('Y-m-d H:i'),
	    'noiztik' => $gaur->modify('-1 day')->format('Y-m-d 00:00'),
	    
	]);
    }    

     /**
     * @Route("/azkenastea", name="admin_eskakizuna_azken_astea", options={"expose" = true})
     */
    public function listAzkenAsteaAction (Request $request){
	$gaur = new \DateTime();
	return $this->redirectToRoute('admin_eskakizuna_list',[
	    'nora' => $gaur->format('Y-m-d H:i'),
	    'noiztik' => $gaur->modify('-7 day')->format('Y-m-d 00:00'),
	    
	]);
    }    

     /**
     * @Route("/", name="admin_eskakizuna_list", options={"expose" = true})
     */
    public function listAction (Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
	$authorization_checker = $this->get('security.authorization_checker');
	$session = $request->getSession();
	$this->_setPageSize($request);
	$returnPage = $this->_getReturnPage($request);

	// Azken bilaketa berreskuratu saioan badago. Horrela bilaketa berriro egin dezakegu.
	$azkenBilaketa = $this->_getAzkenBilaketa($request);
	$azkenBilaketa['role'] = $user->getRoles();
	$from = null;
	$to = null;

	$bilatzaileaForm = $this->createForm(EskakizunaBilatzaileaFormType::class,$azkenBilaketa);

	$bilatzaileaForm->handleRequest($request);
	if ( $bilatzaileaForm->isSubmitted() && $bilatzaileaForm->isValid() ) {
	    $azkenBilaketa = $bilatzaileaForm->getData();
	    $session->set('azkenBilaketa',$azkenBilaketa);
	    $criteria = $bilatzaileaForm->getData();
	    $criteria['role'] = null;
	    $criteria['locale'] = null;
	    if ( $authorization_checker->isGranted('ROLE_KANPOKO_TEKNIKARIA') ) {
		$criteria['enpresa'] = $user->getEnpresa(); 
	    }
	    $from = array_key_exists( 'noiztik', $criteria ) ? $criteria['noiztik'] : null;
	    $to = array_key_exists( 'nora', $criteria ) ? $criteria['nora'] : null;
	    $criteria_without_blanks = $this->_remove_noiztik_nora($criteria);
	}
	
	if ( $authorization_checker->isGranted('ROLE_KANPOKO_TEKNIKARIA') ) {
	    $criteria['enpresa'] = $user->getEnpresa();
	    $criteria = array_merge($azkenBilaketa,$criteria);
	    unset($criteria['role']);
	    unset($criteria['locale']);
	    $criteria_without_blanks = $this->_remove_blank_filters($criteria);
	} else {
	    $criteria = $request->query->all();
	    $from = array_key_exists( 'noiztik', $criteria ) ? $criteria['noiztik'] : null;
	    $to = array_key_exists( 'nora', $criteria ) ? $criteria['nora'] : null;
	    if ( $from !== null ) {
		$bilatzaileaForm->get('noiztik')->setData(new DateTime($from));
	    }
	    if ( $to !== null ) {
		$bilatzaileaForm->get('nora')->setData(new DateTime($to));
	    }
    	    $criteria = array_merge($azkenBilaketa,$criteria);
    	    unset($criteria['role']);
	    unset($criteria['locale']);
	    $criteria_without_blanks = $this->_remove_noiztik_nora($criteria);
	}
//	dump($criteria_without_blanks);die;

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
	    'returnPage' => $returnPage,
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
	    'locale' => $request->getLocale(),
	]);

	$returnPage = $this->_getReturnPage($request);

	$zerbitzuaAldatuAurretik = $eskakizuna->getZerbitzua();
	$erantzunak = $eskakizuna->getErantzunak();
	$eranskinakAldatuAurretik = new ArrayCollection();
	
	foreach ($eskakizuna->getEranskinak() as $eranskina) {
	    $eranskinakAldatuAurretik->add($eranskina);
	}
	
//	dump($eranskinakAldatuAurretik);

	$argazkiakAldatuAurretik = new ArrayCollection();
	
	$aurrekoArgazkia = $eskakizuna->getArgazkia();
	foreach ($eskakizuna->getArgazkiak() as $argazkia) {
	    $argazkiakAldatuAurretik->add($argazkia);
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
		$this->eskakizuna->setEnpresa($zerbitzua->getEnpresa());
		if ( $this->eskakizuna->getEgoera()->getId() === Egoera::EGOERA_BIDALI_GABE 
			    || $zerbitzuaAldatuAurretik !== null && ( $zerbitzua->getId() !== $zerbitzuaAldatuAurretik->getId() )) {
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
	    } else {
		    $egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_BIDALI_GABE);
		    $this->eskakizuna->setEgoera($egoera);
	    }

	    $form_erantzunak = $this->eskakizuna->getErantzunak();
	    $erantzunak_count =  $form_erantzunak->count();
	    $erantzun_berria = $form_erantzunak["erantzuna"];
	    unset($form_erantzunak["erantzuna"]);
	    if ($erantzun_berria !== null ) {
		$erantzuna = new Erantzuna();
		$erantzuna->setErantzulea($user);
		$erantzuna->setEskakizuna($this->eskakizuna);
		$erantzuna->setErantzuna($erantzun_berria);
		$erantzuna->setNoiz(new \DateTime());
		$em->persist($erantzuna);
		$erantzunak = $form_erantzunak->getValues();
		array_push($erantzunak,$erantzuna);
	    }

	    if ($erantzunak_count > 0) {
		$egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_ERANTZUNDA);
		$this->eskakizuna->setEgoera($egoera);
	    }

	    $form_erantzunak = $this->eskakizuna->getErantzunak();
	    $erantzunak_count =  $form_erantzunak->count();
	    $erantzun_berria = $form_erantzunak["erantzuna"];
	    unset($form_erantzunak["erantzuna"]);
	    if ($erantzun_berria !== null ) {
		$erantzuna = new Erantzuna();
		$erantzuna->setErantzulea($user);
		$erantzuna->setEskakizuna($this->eskakizuna);
		$erantzuna->setErantzuna($erantzun_berria);
		$erantzuna->setNoiz(new \DateTime());
		$em->persist($erantzuna);
		$erantzunak = $form_erantzunak->getValues();
		array_push($erantzunak,$erantzuna);
	    }

	    if ($erantzunak_count > 0) {
		$egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_ERANTZUNDA);
		$this->eskakizuna->setEgoera($egoera);
	    }

	    $this->_argazkia_gorde_multi($argazkiakAldatuAurretik);
//	    dump($eranskinakAldatuAurretik);die;
	    $this->_eranskinak_gorde_multi($eranskinakAldatuAurretik);
	    
	    $em->persist($this->eskakizuna);
	    $em->flush();
	    
	    $this->addFlash('success', 'messages.eskakizuna_gordea');
	    
	    return $this->redirectToRoute('admin_eskakizuna_list',[
		'returnPage' => $returnPage
	    ]);
	}
	
	return $this->render('/eskakizuna/edit.html.twig', [
	    'eskakizunaForm' => $form->createView(),
	    'erantzunak' => $erantzunak,
	    'editatzen' => true,
	    'erantzun' => false,
	    'returnPage' => $returnPage,
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

	$returnPage = $this->_getReturnPage($request);
//	dump($returnPage);die;
	$em->remove($eskakizuna);
	$em->flush();
	
	$this->addFlash('success', 'messages.eskakizuna_ezabatua');
	
	return $this->redirectToRoute('admin_eskakizuna_list', [
	    'returnPage' => $returnPage,
	]);
		
    }

    /**
     * @Route("/{id}", name="admin_eskakizuna_show")
     */

    public function showAction (Request $request, Eskakizuna $eskakizuna){
	$user = $this->get('security.token_storage')->getToken()->getUser();
	$this->get('logger')->debug('Show. Eskakizun zenbakia: '.$eskakizuna->getId());
	$argazkien_direktorioa = $this->getParameter('images_uploads_url');

	$returnPage = $this->_getReturnPage($request);

	$eskakizunaForm = $this->createForm(EskakizunaFormType::class, $eskakizuna, [
	    'editatzen' => false,
	    'readonly' => true,
	    'role' => $user->getRoles(),
	    'locale' => $request->getLocale(),
	    ]);

	$erantzunak = $eskakizuna->getErantzunak();
	$eskakizunaForm->handleRequest($request);
	if ( $eskakizunaForm->isSubmitted() && $eskakizunaForm->isValid() ) {
	    $em = $this->getDoctrine()->getManager();
	    $this->eskakizuna = $eskakizunaForm->getData();
            $form_erantzunak = $this->eskakizuna->getErantzunak();
    	    $erantzunak_count =  $form_erantzunak->count();
	    $erantzun_berria = $form_erantzunak["erantzuna"];
	    unset($erantzunak["erantzuna"]);
	    if ($erantzun_berria !== null ) {
		$erantzuna = new Erantzuna();
		$erantzuna->setErantzulea($user);
		$erantzuna->setEskakizuna($this->eskakizuna);
		$erantzuna->setErantzuna($erantzun_berria);
		$erantzuna->setNoiz(new \DateTime());
		$em->persist($erantzuna);
		$erantzunak = $form_erantzunak->getValues();
		array_push($erantzunak,$erantzuna);
	    }
	    if ($this->eskakizuna->getArgazkia() === null) {
		$this->eskakizuna->setArgazkia($eskakizuna->getArgazkia());
	    }

	    // Zerbitzurik ez badauka ez dugu emailik bidaltzen.
	    if ($erantzunak_count > 0 && $this->eskakizuna->getZerbitzua() !== null) {
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
		'erantzunak' => $erantzunak,
		'argazkia' => $argazkien_direktorioa.'/'.$eskakizuna->getArgazkia(),
		'editatzen' => false,
		'erantzun' => true,
		'returnPage' => $returnPage,
	    ]);
	}

	return $this->render('/eskakizuna/show.html.twig', [
	    'eskakizunaForm' => $eskakizunaForm->createView(),
	    'erantzunak' => $erantzunak,
	    'argazkia' => $argazkien_direktorioa.'/'.$eskakizuna->getArgazkia(),
	    'editatzen' => false,
	    'erantzun' => true,
	    'returnPage' => $returnPage,
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
	
	$returnPage = $this->_getReturnPage($request);

	$em = $this->getDoctrine()->getManager();
	$eskakizuna->setItxieraData(new \DateTime());
	$egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_ITXIA);
	$eskakizuna->setEgoera($egoera);

	$em->persist($eskakizuna);
	$em->flush();
	
	$this->addFlash('success', 'messages.eskakizuna_itxia');
	
	return $this->redirectToRoute('admin_eskakizuna_list',[
	    'returnPage' => $returnPage,
	]);
		
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

	$returnPage = $this->_getReturnPage($request);

	$em = $this->getDoctrine()->getManager();
	$eskakizuna->setNoizErreklamatua(new \DateTime());
	$eskakizuna->setNorkErreklamatua($user);
	$title = 'Eskakizuna erreklamatua. Eskakizun zenbakia: ';
	$this->_mezuaBidaliEnpresari ($title, $eskakizuna, $eskakizuna->getEnpresa());
	
	$em->persist($eskakizuna);
	$em->flush();
	
	$this->addFlash('success', 'messages.eskakizuna_erreklamatua');
	
	return $this->redirectToRoute('admin_eskakizuna_list',[
	    'returnPage' => $returnPage,
	]);
		
    }

    private function _getReturnPage(Request $request) {
	if ( $request->query->get('returnPage') != null ) {
	    $returnPage=$request->query->get('returnPage');
	    $request->query->remove('returnPage');
	} else {
	    $returnPage = 1;
	}	
	return $returnPage;
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
    
//    private function _parseEskakizuna($form){
//	$data = $form->getData();
//	$this->eskakizuna->setLep($data->getLep());
//	$this->eskakizuna->setNoiz($data->getNoiz());
//	$this->eskakizuna->setKalea($data->getKalea());
//	$this->eskakizuna->setArgazkia($data->getArgazkia());
//	$this->eskakizuna->setEskakizunMota($data->getEskakizunMota());
//	$this->eskakizuna->setJatorria($data->getJatorria());
//	$this->eskakizuna->setMamia($data->getMamia());
//	if ( $data->getZerbitzua() !== null ) {
//	    $this->eskakizuna->setZerbitzua($data->getZerbitzua());
//	}
//    }

    private function _argazkia_kudeatu(Argazkia $argazkia) {
	$argazkien_direktorioa = $this->getParameter('images_uploads_directory');
	$argazkien_zabalera = $this->getParameter('images_width');
	$argazkien_thumb_zabalera = $this->getParameter('images_thumb_width');
	$argazkiaren_izena = $argazkia->getImageName();
	if ( $argazkia !== null ) {
	    /* Honek funtzionatzen du baina agian zuzenean txikituta gorde daiteke */
	    $image = new \Imagick($argazkien_direktorioa.'/'.$argazkiaren_izena);
	    $image->thumbnailImage($argazkien_zabalera,0);
	    $image->writeImage($argazkien_direktorioa.'/'.$argazkiaren_izena);
	    $imageFile = new File($argazkien_direktorioa.'/'.$argazkiaren_izena);
	    $argazkia->setImageFile($imageFile);
	    $image->thumbnailImage($argazkien_thumb_zabalera,0);
	    $image->writeImage($argazkien_direktorioa.'/'.'thumb-'.$argazkiaren_izena);
	    $imageThumbnailFile = new File($argazkien_direktorioa.'/'.'thumb-'.$argazkiaren_izena);
	    $argazkia->setImageThumbnailFile($imageThumbnailFile);
	    $argazkia->setImageThumbnailSize($imageThumbnailFile->getSize());
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
    
        private function _argazkia_gorde_multi($argazkiakAldatuAurretik = null) {
	$em = $this->getDoctrine()->getManager();
//	dump($argazkiakAldatuAurretik,$this->eskakizuna->getArgazkiak());die;
	if ($argazkiakAldatuAurretik !== null) {
	    foreach ($argazkiakAldatuAurretik as $aurrekoArgazkia ) {
		if (false === $this->eskakizuna->getArgazkiak()->contains($aurrekoArgazkia)) {
		    $aurrekoArgazkia->setEskakizuna(null);
		    $em->persist($aurrekoArgazkia);
		}
	    }
	}
	
	$argazkiak = $this->eskakizuna->getArgazkiak();
	if (!$argazkiak->isEmpty()) {
	    foreach($argazkiak as $argaz) {
		$em->persist($argaz);
		$this->_argazkia_kudeatu($argaz);
		$argaz->setEskakizuna($this->eskakizuna);
	    }
	}
    }

    private function _eranskinak_gorde_multi($eranskinakAldatuAurretik = null) {
	$em = $this->getDoctrine()->getManager();
	/* Zaharretatik borratu direnak borratu */
	if ($eranskinakAldatuAurretik != null && !$eranskinakAldatuAurretik->isEmpty()) {
	    foreach ($eranskinakAldatuAurretik as $aurrekoEranskina ) {
		if (false === $this->eskakizuna->getEranskinak()->contains($aurrekoEranskina)) {
		    $this->eskakizuna->removeEranskinak($aurrekoEranskina);
		    $em->remove($aurrekoEranskina);
		}
	    }
	}
	/* Eranskin berriak edo aldatutakoak gorde */
	$eranskinak = $this->eskakizuna->getEranskinak();
	if (!$eranskinak->isEmpty()) {
	    foreach($eranskinak as $erans) {
		$em->persist($erans);
		$erans->setEskakizuna($this->eskakizuna);
	    }
	}
    }

    private function _getAzkenBilaketa(Request $request) {
	$azkenBilaketa = null;
	$em = $this->getDoctrine()->getManager();
	$session = $request->getSession();
	if ( $session->get('azkenBilaketa') != null ) {
	    $azkenBilaketa = $session->get('azkenBilaketa');
	    if (array_key_exists('egoera', $azkenBilaketa) && $azkenBilaketa['egoera'] != null ) {
		$azkenBilaketa['egoera'] = $em->getRepository(Egoera::class)->find($azkenBilaketa['egoera']);
	    }
	    if (array_key_exists('zerbitzua', $azkenBilaketa) && $azkenBilaketa['zerbitzua'] != null ) {
		$azkenBilaketa['zerbitzua'] = $em->getRepository(Zerbitzua::class)->find($azkenBilaketa['zerbitzua']);
	    }
	    if (array_key_exists('enpresa', $azkenBilaketa) && $azkenBilaketa['enpresa'] != null ) {
		$azkenBilaketa['enpresa'] = $em->getRepository(Enpresa::class)->find($azkenBilaketa['enpresa']);
	    }
	} else {
	    $azkenBilaketa['locale'] = $request->getLocale();
	}
	return $this->_remove_blank_filters($azkenBilaketa);
    }
    
    private function _setPageSize(Request $request) {
	$session = $request->getSession();
	if ( $request->query->get('pageSize') != null ) {
	    $pageSize = $request->query->get('pageSize');
	    $request->query->remove('pageSize');
	    $session->set('pageSize', $pageSize);
	} else {
	    if ( $session->get('pageSize') == null ) {
		$session->set('pageSize', 10);
	    }
	}
    }
}

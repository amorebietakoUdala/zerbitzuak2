<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\Admin;

use AppBundle\Entity\Erabiltzailea;
use AppBundle\Entity\Eskakizuna;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\Web\Admin\ErabiltzaileaFormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ArduradunakController
 *
 * @author ibilbao

/**
* @Route("/{_locale}/admin/erabiltzaileak")
*/
class ErabiltzaileaController extends Controller {
    /**
     * @Route("/new", name="admin_erabiltzailea_new")
     */
    public function newAction (Request $request){
	$form = $this->createForm(ErabiltzaileaFormType::class);
	
	// Only handles data on POST request
	$form->handleRequest($request);
	if ( $form->isSubmitted() && $form->isValid() ) {
	    $erabiltzailea = $form->getData();
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($erabiltzailea);
	    $em->flush();
	    
	    $this->addFlash('success', 'messages.erabiltzailea_gordea');
	    
	    return $this->redirectToRoute('admin_erabiltzailea_list');
	}
	
	return $this->render('admin/erabiltzailea/new.html.twig', [
	    'erabiltzaileaForm' => $form->createView(),
	    'profile' => false
	]);
    }

    /**
     * @Route("/{id}/edit", name="admin_erabiltzailea_edit")
     */
    public function editAction (Request $request, Erabiltzailea $erabiltzailea){
	$form = $this->createForm(ErabiltzaileaFormType::class, $erabiltzailea);

	$aurrekoErabiltzailea = $erabiltzailea;
	// Only handles data on POST request
	$form->handleRequest($request);
	if ( $form->isSubmitted() && $form->isValid() ) {
	    $erabiltzailea = $form->getData();
	    if ( $erabiltzailea->getPlainPassword() !== null || 
		 $aurrekoErabiltzailea->getUsername() !== $erabiltzailea->getUsername() || 
		 $aurrekoErabiltzailea->getEmail() !== $erabiltzailea->getEmail()
		 ) {
                $userManager = $this->get('fos_user.user_manager');
                $userManager->updateUser($erabiltzailea,false);
            }
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($erabiltzailea);
	    $em->flush();
	    
	    $this->addFlash('success', 'messages.erabiltzailea_gordea');
	    
	    return $this->redirectToRoute('admin_erabiltzailea_list');
	}
	
	return $this->render('admin/erabiltzailea/edit.html.twig', [
	    'erabiltzaileaForm' => $form->createView(),
	    'profile' => false
	]);
		
    }
    /**
     * @Route("/{id}/delete", name="admin_erabiltzailea_delete")
     */
    public function deleteAction (Request $request, Erabiltzailea $erabiltzailea){
	if (!$erabiltzailea) {
	    $this->addFlash('error', 'messages.erabiltzailea_ez_da_existitzen');
	    return $this->redirectToRoute('admin_erabiltzailea_list');
	}

	$em = $this->getDoctrine()->getManager();
	$eskakizunak = $em->getRepository(Eskakizuna::class)->findOneBy([
	    'norkInformatua' => $erabiltzailea 
	]);

	if ($eskakizunak) {
	    $this->addFlash('error', 'messages.ezin_erabiltzailea_borratu_eskakizunak_dituelako');
	    return $this->redirectToRoute('admin_erabiltzailea_list');
	} else {
	    $eskakizunakErreklamatu = $em->getRepository(Eskakizuna::class)->findOneBy([
	        'norkErreklamatua' => $erabiltzailea 
	    ]);
	    if ($eskakizunakErreklamatu) {
		$this->addFlash('error', 'messages.ezin_erabiltzailea_borratu_eskakizunak_erreklamatu_dituelako');
		return $this->redirectToRoute('admin_erabiltzailea_list');
	    }
	}
	
	$em->remove($erabiltzailea);
	$em->flush();
	
	$this->addFlash('success', 'messages.erabiltzailea_ezabatua');
	
	return $this->redirectToRoute('admin_erabiltzailea_list');
		
    }

    /**
     * @Route("/{id}", name="admin_erabiltzailea_show")
     */

    public function showAction (Erabiltzailea $erabiltzailea){
	$this->get('logger')->debug('Showing: '.$erabiltzailea->getId());
	$form = $this->createForm(ErabiltzaileaFormType::class, $erabiltzailea);
	return $this->render('admin/erabiltzailea/show.html.twig', [
	    'erabiltzaileaForm' => $form->createView(),
	    'profile' => false
	]);
    }

    /**
     * @Route("/", name="admin_erabiltzailea_list", options={"expose" = true})
     */
    public function listAction (){
	$em = $this->getDoctrine()->getManager();
	$erabiltzaileak = $em->getRepository('AppBundle:Erabiltzailea')->findAll();
	return $this->render('admin/erabiltzailea/list.html.twig', [
	    'erabiltzaileak' => $erabiltzaileak,
	]);
    }



}

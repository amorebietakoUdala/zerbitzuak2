<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\Admin;

use AppBundle\Entity\Enpresa;
use AppBundle\Entity\Eskakizuna;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\Web\Admin\EnpresaFormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of EnpresaController
 *
 * @author ibilbao

/**
* @Route("/{_locale}/admin/enpresa")
*/
class EnpresaController extends Controller {
    /**
     * @Route("/new", name="admin_enpresa_new")
     */
    public function newAction (Request $request){
	$form = $this->createForm(EnpresaFormType::class);
	
	// Only handles data on POST request
	$form->handleRequest($request);
	if ( $form->isSubmitted() && $form->isValid() ) {
	    $enpresa = $form->getData();
	    
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($enpresa);
	    $em->flush();
	    
	    $this->addFlash('success', 'messages.enpresa_gordea');
	    
	    return $this->redirectToRoute('admin_enpresa_list');
	}
	
	return $this->render('admin/enpresa/new.html.twig', [
	    'enpresaForm' => $form->createView()
	]);
    }

    /**
     * @Route("/{id}/edit", name="admin_enpresa_edit")
     */
    public function editAction (Request $request, Enpresa $enpresa){
	$form = $this->createForm(EnpresaFormType::class, $enpresa);
	
	// Only handles data on POST request
	$form->handleRequest($request);
	if ( $form->isSubmitted() && $form->isValid() ) {
	    $enpresa = $form->getData();
	    
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($enpresa);
	    $em->flush();
	    
	    $this->addFlash('success', 'messages.enpresa_gordea');
	    
	    return $this->redirectToRoute('admin_enpresa_list');
	}
	
	return $this->render('admin/enpresa/edit.html.twig', [
	    'enpresaForm' => $form->createView()
	]);
		
    }
    /**
     * @Route("/{id}/delete", name="admin_enpresa_delete")
     */
    public function deleteAction (Request $request, Enpresa $enpresa){
	if (!$enpresa) {
	    $this->addFlash('error', 'messages.enpresa_ez_da_existitzen');
	    return $this->redirectToRoute('admin_enpresa_list');
	}

	$em = $this->getDoctrine()->getManager();
	$eskakizunak = $em->getRepository(Eskakizuna::class)->findOneBy([
	    'enpresa' => $enpresa 
	]);

	if ($eskakizunak) {
	    $this->addFlash('error', 'messages.ezin_enpresa_borratu_eskakizunak_dituelako');
	    return $this->redirectToRoute('admin_enpresa_list');
	}
	
	$em->remove($enpresa);
	$em->flush();
	
	$this->addFlash('success', 'messages.enpresa_ezabatua');
	
	return $this->redirectToRoute('admin_enpresa_list');
		
    }

    /**
     * @Route("/{id}", name="admin_enpresa_show"))
     */

    public function showAction (Enpresa $enpresa){
	$this->get('logger')->debug('EnpresaController->showAction->Enpresa->'.$enpresa->__toDebug());
	$form = $this->createForm(EnpresaFormType::class, $enpresa);
	return $this->render('admin/enpresa/show.html.twig', [
	    'enpresaForm' => $form->createView()
	]);
    }

    /**
     * @Route("/", name="admin_enpresa_list", options={"expose" = true})
     */
    public function listAction (){
	$em = $this->getDoctrine()->getManager();
	$enpresak = $em->getRepository('AppBundle:Enpresa')->findAll();

	return $this->render('admin/enpresa/list.html.twig', [
	    'enpresak' => $enpresak,
	]);
    }



}

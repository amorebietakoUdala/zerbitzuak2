<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\Admin;

use AppBundle\Entity\Jatorria;
use AppBundle\Entity\Eskakizuna;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\Web\Admin\JatorriaFormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of JatorriaController
 *
 * @author ibilbao

/**
* @Route("/{_locale}/admin/jatorria")
*/
class JatorriaController extends Controller {
    /**
     * @Route("/new", name="admin_jatorria_new")
     */
    public function newAction (Request $request){
	$form = $this->createForm(JatorriaFormType::class);
	
	// Only handles data on POST request
	$form->handleRequest($request);
	if ( $form->isSubmitted() && $form->isValid() ) {
	    $jatorria = $form->getData();
	    
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($jatorria);
	    $em->flush();
	    
	    $this->addFlash('success', 'messages.jatorria_gordea');
	    
	    return $this->redirectToRoute('admin_jatorria_list');
	}
	
	return $this->render('admin/jatorria/new.html.twig', [
	    'jatorriaForm' => $form->createView()
	]);
    }

    /**
     * @Route("/{id}/edit", name="admin_jatorria_edit")
     */
    public function editAction (Request $request, Jatorria $jatorria){
	$form = $this->createForm(JatorriaFormType::class, $jatorria);
	
	// Only handles data on POST request
	$form->handleRequest($request);
	if ( $form->isSubmitted() && $form->isValid() ) {
	    $jatorria = $form->getData();
	    
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($jatorria);
	    $em->flush();
	    
	    $this->addFlash('success', 'messages.jatorria_gordea');
	    
	    return $this->redirectToRoute('admin_jatorria_list');
	}
	
	return $this->render('admin/jatorria/edit.html.twig', [
	    'jatorriaForm' => $form->createView()
	]);
		
    }
    /**
     * @Route("/{id}/delete", name="admin_jatorria_delete")
     */
    public function deleteAction (Request $request, Jatorria $jatorria){
	if (!$jatorria) {
	    $this->addFlash('error', 'messages.jatorria_ez_da_existitzen');
	    return $this->redirectToRoute('admin_jatorria_list');
	}

	$em = $this->getDoctrine()->getManager();
	$eskakizunak = $em->getRepository(Eskakizuna::class)->findOneBy([
	    'jatorria' => $jatorria
	]);

	if ($eskakizunak) {
	    $this->addFlash('error', 'messages.ezin_jatorria_borratu_eskakizunak_dituelako');
	    return $this->redirectToRoute('admin_jatorria_list');
	}
	
	$em->remove($jatorria);
	$em->flush();
	
	$this->addFlash('success', 'messages.jatorria_ezabatua');
	
	return $this->redirectToRoute('admin_jatorria_list');
		
    }

    /**
     * @Route("/{id}", name="admin_jatorria_show"))
     */

    public function showAction (Jatorria $jatorria){
	$this->get('logger')->debug('Showing: '.$jatorria->getId());
	$form = $this->createForm(JatorriaFormType::class, $jatorria);
	return $this->render('admin/jatorria/show.html.twig', [
	    'jatorriaForm' => $form->createView()
	]);
    }

    /**
     * @Route("/", name="admin_jatorria_list", options={"expose" = true})
     */
    public function listAction (){
	$em = $this->getDoctrine()->getManager();
	$jatorriak = $em->getRepository('AppBundle:Jatorria')->findAll();

	return $this->render('admin/jatorria/list.html.twig', [
	    'jatorriak' => $jatorriak,
	]);
    }



}

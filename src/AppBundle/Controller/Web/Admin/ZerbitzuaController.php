<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\Admin;

use AppBundle\Entity\Zerbitzua;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\Web\Admin\ZerbitzuaFormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of EnpresaController
 *
 * @author ibilbao

/**
* @Route("/{_locale}/admin/zerbitzua")
*/
class ZerbitzuaController extends Controller {
    /**
     * @Route("/new", name="admin_zerbitzua_new")
     */
    public function newAction (Request $request){
	$form = $this->createForm(ZerbitzuaFormType::class);
	
	// Only handles data on POST request
	$form->handleRequest($request);
	if ( $form->isSubmitted() && $form->isValid() ) {
	    $zerbitzua = $form->getData();
	    
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($zerbitzua);
	    $em->flush();
	    
	    $this->addFlash('success', 'messages.zerbitzua_gordea');
	    
	    return $this->redirectToRoute('admin_zerbitzua_list');
	}
	
	return $this->render('admin/zerbitzua/new.html.twig', [
	    'zerbitzuaForm' => $form->createView()
	]);
    }

    /**
     * @Route("/{id}/edit", name="admin_zerbitzua_edit")
     */
    public function editAction (Request $request, Zerbitzua $zerbitzua){
	$form = $this->createForm(ZerbitzuaFormType::class, $zerbitzua);
	
	// Only handles data on POST request
	$form->handleRequest($request);
	if ( $form->isSubmitted() && $form->isValid() ) {
	    $zerbitzua = $form->getData();
	    
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($zerbitzua);
	    $em->flush();
	    
	    $this->addFlash('success', 'messages.zerbitzua_gordea');
	    
	    return $this->redirectToRoute('admin_zerbitzua_list');
	}
	
	return $this->render('admin/zerbitzua/edit.html.twig', [
	    'zerbitzuaForm' => $form->createView()
	]);
		
    }
    /**
     * @Route("/{id}/delete", name="admin_zerbitzua_delete")
     */
    public function deleteAction (Request $request, $id){
	$em = $this->getDoctrine()->getManager();
	$zerbitzua = $em->getRepository(Zerbitzua::class)->findOneBy([
	    'id' => $id 
	]);

	if (!$zerbitzua) {
	    $this->addFlash('error', 'messages.zerbitzua_ez_da_existitzen');
	    return $this->listAction();
	}
	
	$em = $this->getDoctrine()->getManager();
	$em->remove($zerbitzua);
	$em->flush();
	
	$this->addFlash('success', 'messages.zerbitzua_ezabatua');
	
	return $this->redirectToRoute('admin_zerbitzua_list');
		
    }

    /**
     * @Route("/{id}", name="admin_zerbitzua_show", options={"expose" = true}))
     */

    public function showAction (Zerbitzua $zerbitzua){
	$this->get('logger')->debug('Showing: '.$zerbitzua->getId());
	$form = $this->createForm(ZerbitzuaFormType::class, $zerbitzua);
	return $this->render('admin/zerbitzua/show.html.twig', [
	    'zerbitzuaForm' => $form->createView()
	]);
    }

    /**
     * @Route("/", name="admin_zerbitzua_list", options={"expose" = true})
     */
    public function listAction (){
	$em = $this->getDoctrine()->getManager();
	$zerbitzuak = $em->getRepository('AppBundle:Zerbitzua')->findAll();

	return $this->render('admin/zerbitzua/list.html.twig', [
	    'zerbitzuak' => $zerbitzuak,
	]);
    }



}

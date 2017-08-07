<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\User;

use AppBundle\Controller\Web\User\EskakizunaFormType;
use AppBundle\Entity\Eskakizuna;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of ErantzunaController
 *
 * @author ibilbao

/**
* @Route("{_locale}/eskakizuna/{id}/erantzuna")
*/
class ErantzunaController extends Controller {
    /**
     * @Route("/new", name="admin_erantzuna_new", options={"expose" = true})
     */
    public function newAction (Request $request, $id){
//	$user = $this->get('security.token_storage')->getToken()->getUser();
//	$form = $this->createForm(ErantzunaFormType::class);
//	
//	// Only handles data on POST request
//	$form->handleRequest($request);
//	if ( $form->isSubmitted() && $form->isValid() ) {
//	    $em = $this->getDoctrine()->getManager();
//	    $eskakizuna = $em->getRepository(Eskakizuna::class)->find($id);
//            $data = $form->getData();
//	    $data->setEskakizuna($eskakizuna);
//	    $data->setErantzulea($user);
//	    $em->persist($data);
//	    $em->flush();
//	    $this->addFlash('success', 'messages.eskakizuna_gordea');
//	    $eskakizunaForm = $this->createForm(EskakizunaFormType::class,$eskakizuna);
//	}
//	
//	return $this->render('/eskakizuna/edit.html.twig', [
//	    'eskakizunaForm' => $eskakizunaForm,
//	    'argazkia' => null
//	]);
    }

    /**
     * @Route("/{erantzuna_id}/edit", name="admin_erantzuna_edit")
     */
    public function editAction (Request $request, Eskakizuna $eskakizuna){
	// TODO
    }
    /**
     * @Route("/erantzuna_id/delete", name="admin_erantzuna_delete")
     */
    public function deleteAction (Request $request, $id){
	// TODO
    }

    /**
     * @Route("/{erantzuna_id}", name="admin_erantzuna_show")
     */

    public function showAction (Eskakizuna $eskakizuna){
	//TODO
    }

    /**
     * @Route("/", name="admin_erantzuna_list", options={"expose" = true})
     */
    public function listAction (){
	// TODO 
    }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\Admin;

use AppBundle\Entity\Eskakizuna;
use AppBundle\Entity\Eskatzailea;
use AppBundle\Forms\EskatzaileaFormType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of EskatzaileaController.
 *
 * @author ibilbao

 /**
 * @Route("/{_locale}/admin/eskatzailea")
 */
class EskatzaileaController extends Controller
{
    /**
     * @Route("/new", name="admin_eskatzailea_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(EskatzaileaFormType::class);

        // Only handles data on POST request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eskatzailea = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($eskatzailea);
            $em->flush();

            $this->addFlash('success', 'messages.eskatzailea_gordea');

            return $this->redirectToRoute('admin_eskatzailea_list');
        }

        return $this->render('admin/eskatzailea/new.html.twig', [
        'eskatzaileaForm' => $form->createView(),
    ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_eskatzailea_edit")
     */
    public function editAction(Request $request, Eskatzailea $eskatzailea)
    {
        $form = $this->createForm(EskatzaileaFormType::class, $eskatzailea);

        // Only handles data on POST request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eskatzailea = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($eskatzailea);
            $em->flush();

            $this->addFlash('success', 'messages.eskatzailea_gordea');

            return $this->redirectToRoute('admin_eskatzailea_list');
        }

        return $this->render('admin/eskatzailea/edit.html.twig', [
        'eskatzaileaForm' => $form->createView(),
    ]);
    }

    /**
     * @Route("/{id}/delete", name="admin_eskatzailea_delete")
     */
    public function deleteAction(Request $request, Eskatzailea $eskatzailea)
    {
        if (!$eskatzailea) {
            $this->addFlash('error', 'messages.eskatzailea_ez_da_existitzen');

            return $this->redirectToRoute('admin_eskatzailea_list');
        }

        $em = $this->getDoctrine()->getManager();
        $eskakizunak = $em->getRepository(Eskakizuna::class)->findOneBy([
        'eskatzailea' => $eskatzailea,
    ]);

        if ($eskakizunak) {
            $this->addFlash('error', 'messages.ezin_eskatzailea_borratu_eskakizunak_dituelako');

            return $this->redirectToRoute('admin_eskatzailea_list');
        }

        $em->remove($eskatzailea);
        $em->flush();

        $this->addFlash('success', 'messages.eskatzailea_ezabatua');

        return $this->redirectToRoute('admin_eskatzailea_list');
    }

    /**
     * @Route("/{id}", name="admin_eskatzailea_show"))
     */
    public function showAction(Eskatzailea $eskatzailea, LoggerInterface $logger)
    {
        $logger->debug('Showing: '.$eskatzailea->getId());
        $form = $this->createForm(EskatzaileaFormType::class, $eskatzailea);

        return $this->render('admin/eskatzailea/show.html.twig', [
        'eskatzaileaForm' => $form->createView(),
    ]);
    }

    /**
     * @Route("/", name="admin_eskatzailea_list", options={"expose" = true})
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $eskatzaileak = $em->getRepository('AppBundle:Eskatzailea')->findAll();

        return $this->render('admin/eskatzailea/list.html.twig', [
        'eskatzaileak' => $eskatzaileak,
    ]);
    }
}

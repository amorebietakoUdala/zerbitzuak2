<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\Admin;

use AppBundle\Entity\Eskakizuna;
use AppBundle\Entity\EskakizunMota;
use AppBundle\Forms\EskakizunMotaFormType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of EskakizunMotaController.
 *
 * @author ibilbao

 /**
 * @Route("/{_locale}/admin/eskakizun_mota")
 */
class EskakizunMotaController extends Controller
{
    /**
     * @Route("/new", name="admin_eskakizun_mota_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(EskakizunMotaFormType::class);

        // Only handles data on POST request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eskakizunmota = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($eskakizunmota);
            $em->flush();

            $this->addFlash('success', 'messages.eskakizun_mota_gordea');

            return $this->redirectToRoute('admin_eskakizun_mota_list');
        }

        return $this->render('admin/eskakizun_mota/new.html.twig', [
        'eskakizunMotaForm' => $form->createView(),
    ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_eskakizun_mota_edit")
     */
    public function editAction(Request $request, EskakizunMota $eskakizunmota)
    {
        $form = $this->createForm(EskakizunMotaFormType::class, $eskakizunmota);

        // Only handles data on POST request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eskakizunmota = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($eskakizunmota);
            $em->flush();

            $this->addFlash('success', 'messages.eskakizun_mota_gordea');

            return $this->redirectToRoute('admin_eskakizun_mota_list');
        }

        return $this->render('admin/eskakizun_mota/edit.html.twig', [
        'eskakizunMotaForm' => $form->createView(),
    ]);
    }

    /**
     * @Route("/{id}/delete", name="admin_eskakizun_mota_delete")
     */
    public function deleteAction(Request $request, EskakizunMota $eskakizunMota)
    {
        if (!$eskakizunMota) {
            $this->addFlash('error', 'messages.eskakizun_mota_ez_da_existitzen');

            return $this->redirectToRoute('admin_eskakizun_mota_list');
        }

        $em = $this->getDoctrine()->getManager();
        $eskakizunak = $em->getRepository(Eskakizuna::class)->findOneBy([
        'eskakizunMota' => $eskakizunMota,
    ]);

        if ($eskakizunak) {
            $this->addFlash('error', 'messages.ezin_eskakizun_mota_borratu_eskakizunak_dituelako');

            return $this->redirectToRoute('admin_eskakizun_mota_list');
        }

        $em->remove($eskakizunMota);
        $em->flush();

        $this->addFlash('success', 'messages.eskakizun_mota_ezabatua');

        return $this->redirectToRoute('admin_eskakizun_mota_list');
    }

    /**
     * @Route("/{id}", name="admin_eskakizun_mota_show"))
     */
    public function showAction(EskakizunMota $eskakizunmota, LoggerInterface $logger)
    {
        $logger->debug('Showing: '.$eskakizunmota->getId());
        $form = $this->createForm(EskakizunMotaFormType::class, $eskakizunmota);

        return $this->render('admin/eskakizun_mota/show.html.twig', [
        'eskakizunMotaForm' => $form->createView(),
    ]);
    }

    /**
     * @Route("/", name="admin_eskakizun_mota_list", options={"expose" = true})
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $eskakizunmotak = $em->getRepository('AppBundle:EskakizunMota')->findAll();

        return $this->render('admin/eskakizun_mota/list.html.twig', [
        'eskakizunmotak' => $eskakizunmotak,
    ]);
    }
}

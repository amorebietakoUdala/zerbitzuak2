<?php

/**
 * Description of EgoeraController.
 *
 * @author ibilbao
*/

namespace App\Controller;

use App\Entity\Egoera;
use App\Entity\Eskakizuna;
use App\Form\EgoeraFormType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

 /**
 * @Route("/{_locale}/admin/egoera")
 */
class EgoeraController extends AbstractController
{
    /**
     * @Route("/new", name="admin_egoera_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(EgoeraFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $egoera = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($egoera);
            $em->flush();

            $this->addFlash('success', 'messages.egoera_gordea');

            return $this->redirectToRoute('admin_egoera_list');
        }

        return $this->render('admin/egoera/new.html.twig', [
        'egoeraForm' => $form->createView(),
    ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_egoera_edit")
     */
    public function editAction(Request $request, Egoera $egoera)
    {
        $form = $this->createForm(EgoeraFormType::class, $egoera);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $egoera = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($egoera);
            $em->flush();

            $this->addFlash('success', 'messages.egoera_gordea');

            return $this->redirectToRoute('admin_egoera_list');
        }

        return $this->render('admin/egoera/edit.html.twig', [
        'egoeraForm' => $form->createView(),
    ]);
    }

    /**
     * @Route("/{id}/delete", name="admin_egoera_delete")
     */
    public function deleteAction(Request $request, Egoera $egoera)
    {
        if (!$egoera) {
            $this->addFlash('error', 'messages.egoera_ez_da_existitzen');

            return $this->redirectToRoute('admin_egoera_list');
        }

        $em = $this->getDoctrine()->getManager();
        $eskakizunak = $em->getRepository(Eskakizuna::class)->findOneBy([
        'egoera' => $egoera,
    ]);

        if ($eskakizunak) {
            $this->addFlash('error', 'messages.ezin_egoera_borratu_eskakizunak_dituelako');

            return $this->redirectToRoute('admin_egoera_list');
        }

        $em->remove($egoera);
        $em->flush();

        $this->addFlash('success', 'messages.egoera_ezabatua');

        return $this->redirectToRoute('admin_egoera_list');
    }

    /**
     * @Route("/{id}", name="admin_egoera_show"))
     */
    public function showAction(Egoera $egoera, LoggerInterface $logger)
    {
        $logger->debug('EgoeraController->showAction->Egoera->'.$egoera->__toDebug());
        //	$logger->debug('Showing: '.$egoera->__toDebug());
        $form = $this->createForm(EgoeraFormType::class, $egoera);
        $logger->debug('EgoeraController->showAction->Egoera->render: admin/egoera/show.html.twig');

        return $this->render('admin/egoera/show.html.twig', [
        'egoeraForm' => $form->createView(),
    ]);
    }

    /**
     * @Route("/", name="admin_egoera_list", options={"expose" = true})
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $egoerak = $em->getRepository('App:Egoera')->findAll();

        return $this->render('admin/egoera/list.html.twig', [
        'egoerak' => $egoerak,
    ]);
    }
}

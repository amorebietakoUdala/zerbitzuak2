<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\Eskakizuna;
use App\Form\UserFormType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use AMREU\UserBundle\Doctrine\UserManager;
use App\Entity\Erantzuna;

/**
 * Description of ErabiltzaileakController.
 *
 * @author ibilbao
 */

class ErabiltzaileaController extends AbstractController
{

    /**
     * @Route("/{_locale}/profile", name="user_profile_action")
     * @IsGranted("ROLE_USER")
     */
    public function profleAction(Request $request, LoggerInterface $logger, UserManager $userManager)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $logger->info('Showing: '.$user->getId());

        $em = $this->getDoctrine()->getManager();
//        $user = $em->getRepository(User::class)->find($user->getId());
        $userForm = $this->createForm(UserFormType::class, $user, [
            'profile' => true,
            'password_change' => true
        ]);

        $previousPassword = $user->getPassword();
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user = $userForm->getData();
            if ('nopassword' === $user->getPassword()) {
                $user->setPassword($previousPassword);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            } else {
            // This updates and persist the new password, no need to persist it again.
                $userManager->updatePassword($user, $user->getPassword());
            }
            $this->addFlash('success', 'messages.erabiltzailea_gordea');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('/admin/erabiltzailea/edit.html.twig', [
            'erabiltzaileaForm' => $userForm->createView(),
            'profile' => true,
            'password_change' => true
    ]);
    }    
    /**
     * @Route("/{_locale}/admin/erabiltzaileak/new", name="admin_erabiltzailea_new")
     * @IsGranted("ROLE_ADMIN")
     */
    public function newAction(Request $request, UserManager $userManager)
    {
        $form = $this->createForm(UserFormType::class, null, [
            'password_change' => true
        ]);

        // Only handles data on POST request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $aurkitutako_erabiltzailea = $em->getRepository(User::class)->findOneBy([
                'username' => $user->getUsername(),
            ]);

            if ($aurkitutako_erabiltzailea) {
                $this->addFlash('error', 'messages.erabiltzailea_hori_sortuta_dago_jadanik');

                return $this->render('admin/erabiltzailea/new.html.twig', [
                    'erabiltzaileaForm' => $form->createView(),
                    'profile' => false,
                ]);
            }

            $aurkitutako_erabiltzailea = $em->getRepository(User::class)->findOneBy([
                'email' => $user->getEmail(),
            ]);
            if ($aurkitutako_erabiltzailea) {
                $this->addFlash('error', 'messages.erabiltzailea_hori_sortuta_dago_jadanik');

                return $this->render('admin/erabiltzailea/new.html.twig', [
                    'erabiltzaileaForm' => $form->createView(),
                    'profile' => false,
                ]);
            }
            // This persists the user no need to persist again
            $userManager->updatePassword($user, $user->getPassword());

            $this->addFlash('success', 'messages.erabiltzailea_gordea');

            return $this->redirectToRoute('admin_erabiltzailea_list');
        }
        return $this->render('admin/erabiltzailea/new.html.twig', [
            'erabiltzaileaForm' => $form->createView(),
            'profile' => false,
            'password_change' => true
    ]);
    }

    /**
     * @Route("/{_locale}/admin/erabiltzaileak/{id}/edit", name="admin_erabiltzailea_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editAction(Request $request, User $user, UserManager $userManager)
    {
        $form = $this->createForm(UserFormType::class, $user, [
            'password_change' => true
        ]);

        $previousPassword = $user->getPassword();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if ('nopassword' === $user->getPassword()) {
                $user->setPassword($previousPassword);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            } else {
            // This updates and persist the new password, no need to persist it again.
                $userManager->updatePassword($user, $user->getPassword());
            }
            $this->addFlash('success', 'messages.erabiltzailea_gordea');

            return $this->redirectToRoute('admin_erabiltzailea_list');
        }
        return $this->render('admin/erabiltzailea/edit.html.twig', [
            'erabiltzaileaForm' => $form->createView(),
            'profile' => false,
        ]);
    }

    /**
     * @Route("/{_locale}/admin/erabiltzaileak/{id}/delete", name="admin_erabiltzailea_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteAction(Request $request, User $user)
    {
        if (!$user) {
            $this->addFlash('error', 'messages.erabiltzailea_ez_da_existitzen');

            return $this->redirectToRoute('admin_erabiltzailea_list');
        }

        $em = $this->getDoctrine()->getManager();
        $eskakizunak = $em->getRepository(Eskakizuna::class)->findOneBy([
            'norkInformatua' => $user,
        ]);

        if ($eskakizunak) {
            $this->addFlash('error', 'messages.ezin_erabiltzailea_borratu_eskakizunak_dituelako');

            return $this->redirectToRoute('admin_erabiltzailea_list');
        } else {
            $eskakizunakErreklamatu = $em->getRepository(Eskakizuna::class)->findOneBy([
                'norkErreklamatua' => $user,
            ]);
            if ($eskakizunakErreklamatu) {
                $this->addFlash('error', 'messages.ezin_erabiltzailea_borratu_eskakizunak_erreklamatu_dituelako');

                return $this->redirectToRoute('admin_erabiltzailea_list');
            }
            $erantzun = $em->getRepository(Erantzuna::class)->findOneBy([
                'erantzulea' => $user,
            ]);
            if ($erantzun) {
                $this->addFlash('error', 'messages.ezin_erabiltzailea_borratu_erantzunak_sartu_dituelako');

                return $this->redirectToRoute('admin_erabiltzailea_list');
            }

        }

        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'messages.erabiltzailea_ezabatua');

        return $this->redirectToRoute('admin_erabiltzailea_list');
    }

    /**
     * @Route("/{_locale}/admin/erabiltzaileak/{id}", name="admin_erabiltzailea_show")
     * @IsGranted("ROLE_ADMIN")
     */
    public function showAction(User $erabiltzailea, LoggerInterface $logger)
    {
        $logger->debug('Showing: '.$erabiltzailea->getId());
        $form = $this->createForm(UserFormType::class, $erabiltzailea);

        return $this->render('admin/erabiltzailea/show.html.twig', [
        'erabiltzaileaForm' => $form->createView(),
        'profile' => false,
    ]);
    }

    /**
     * @Route("/{_locale}/admin/erabiltzaileak/", name="admin_erabiltzailea_list", options={"expose" = true})
     * @IsGranted("ROLE_ADMIN")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $erabiltzaileak = $em->getRepository('App:User')->findAll();

        return $this->render('admin/erabiltzailea/list.html.twig', [
        'erabiltzaileak' => $erabiltzaileak,
    ]);
    }
}

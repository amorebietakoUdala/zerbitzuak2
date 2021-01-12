<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/{_locale}/profile", name="user_profile_action")
     * @IsGranted("ROLE_USER")
     */
    public function profleAction(Request $request, LoggerInterface $logger)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $logger->info('Showing: '.$user->getId());

        $em = $this->getDoctrine()->getManager();
        $erabiltzailea = $em->getRepository(User::class)->find($user->getId());
        $erabiltzaileaForm = $this->createForm(UserFormType::class, $erabiltzailea, [
        'profile' => true,
    ]);

        $aurrekoErabiltzailea = $erabiltzailea->getUsername();
        $erabiltzaileaForm->handleRequest($request);

        if ($erabiltzaileaForm->isSubmitted() && $erabiltzaileaForm->isValid()) {
            $erabiltzailea = $erabiltzaileaForm->getData();
            if (null !== $erabiltzailea->getPlainPassword() || $aurrekoErabiltzailea !== $erabiltzailea->getUsername()) {
                $userManager = $this->get('fos_user.user_manager');
                $userManager->updateUser($erabiltzailea, false);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($erabiltzailea);
            $em->flush();

            $this->addFlash('success', 'messages.erabiltzailea_gordea');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('/admin/erabiltzailea/edit.html.twig', [
        'erabiltzaileaForm' => $erabiltzaileaForm->createView(),
        'profile' => true,
    ]);
    }
}

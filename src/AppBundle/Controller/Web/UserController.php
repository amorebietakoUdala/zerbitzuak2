<?php

namespace AppBundle\Controller\Web;

use AppBundle\Controller\Web\Admin\ErabiltzaileaFormType;
use AppBundle\Entity\Erabiltzailea;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Security("has_role('ROLE_USER')")
     */
    public function homeAction(Request $request)
    {
	$user = $this->get('security.token_storage')->getToken()->getUser();
        $authorization_checker = $this->get('security.authorization_checker');
	$locale = $request->attributes->get('_locale');
	if ( $locale !== null ) {
	    $request->getSession()->set('_locale', $locale);
	} else {
	    $request->setLocale($request->getSession()->get('_locale'));
	}
        if ( $authorization_checker->isGranted('ROLE_INFORMATZAILEA') ) {
	    return $this->redirectToRoute('admin_eskakizuna_new',[
		'_locale' => $request->getLocale()
	    ]);
        }
        else 
	    if ( $authorization_checker->isGranted('ROLE_ARDURADUNA') ) {
		return $this->redirectToRoute('admin_eskakizuna_list',[
		'_locale' => $request->getLocale()
	    ]);
            } else if ( $authorization_checker->isGranted('ROLE_ADMIN') ) {
		  return $this->redirectToRoute('admin_eskakizuna_list',[
		'_locale' => $request->getLocale()
	    ]);
	    } else if ( $authorization_checker->isGranted('ROLE_KANPOKO_TEKNIKARIA')) {
		$this->get('logger')->debug('Kanpoko Teknikaria erabiltzailea: '.$user->getUsername());
		return $this->redirectToRoute('admin_eskakizuna_list',[
		'_locale' => $request->getLocale()
	    ]);
	    }
    }

    /**
     * @Route("/{_locale}/profile", name="user_profile_action")
     * @Security("has_role('ROLE_USER')")
     */
    public function profleAction (Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
	$this->get('logger')->info('Showing: '.$user->getId());
	
	$em = $this->getDoctrine()->getManager();
	$erabiltzailea = $em->getRepository(Erabiltzailea::class)->find($user->getId());
	$erabiltzaileaForm = $this->createForm(ErabiltzaileaFormType::class, $erabiltzailea, [
	    'profile' => true,
	]);
	
	$aurrekoErabiltzailea = $erabiltzailea->getUsername();
	$erabiltzaileaForm->handleRequest($request);
	
	if ( $erabiltzaileaForm->isSubmitted() && $erabiltzaileaForm->isValid() ) {
	    $erabiltzailea = $erabiltzaileaForm->getData();
	    if ($erabiltzailea->getPlainPassword() !== null || $aurrekoErabiltzailea !== $erabiltzailea->getUsername()) {
                $userManager = $this->get('fos_user.user_manager');
                $userManager->updateUser($erabiltzailea,false);
            }
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($erabiltzailea);
	    $em->flush();
	    
	    $this->addFlash('success', 'messages.erabiltzailea_gordea');
	    
	    return $this->redirectToRoute('homepage');
	}

	return $this->render('/admin/erabiltzailea/edit.html.twig', [
	    'erabiltzaileaForm' => $erabiltzaileaForm->createView(),
	    'profile' => true
	]);
    }


    }

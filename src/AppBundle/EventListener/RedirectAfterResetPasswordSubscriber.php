<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Description of RedirectAfterRegistrationSubscriber
 *
 * @author ibilbao
 */
class RedirectAfterResetPasswordSubscriber implements EventSubscriberInterface {
    private $router;
        
    public function __construct( RouterInterface $router) {
        $this->router = $router;
    }

    public function onResetPasswordSuccess( FormEvent $event ) {
//	$this->router->get('security.context')->setToken(null);
//	$this->router->getSession()->invalidate();
        $url = $this->router->generate('fos_user_security_logout');
        $response = new RedirectResponse($url);
	$response->headers->clearCookie('REMEMBERME'); 
        $event->setResponse($response);
    }


    public static function getSubscribedEvents() {
        return [
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResetPasswordSuccess'
        ];
    }

}

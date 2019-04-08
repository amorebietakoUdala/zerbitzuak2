<?php

namespace AppBundle\Controller\API;

use AppBundle\Controller\Web\Admin\EskatzaileaFormType;
use AppBundle\Controller\Web\User\ErantzunaFormType;
use AppBundle\Entity\Erabiltzailea;
use AppBundle\Entity\Erantzuna;
use AppBundle\Entity\Eskakizuna;
use AppBundle\Entity\Eskatzailea;
use AppBundle\Entity\Egoera;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use function GuzzleHttp\json_decode;

/**
 * @Route("/api")
 */
class ApiController extends BaseController
{
    /**
     * @Route("/eskatzailea")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $eskatzailea = new Eskatzailea();
        $form = $this->createForm(EskatzaileaFormType::class, $eskatzailea);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($eskatzailea);
        $em->flush();

        $response = $this->createApiResponse($eskatzailea, 201);
        $eskatzaileaUrl = $this->generateUrl(
            'api_eskatzailea_show',
            ['id' => $eskatzailea->getId()]
        );
        $response->headers->set('Location', $eskatzaileaUrl);

        return $response;
    }

    /**
     * @Route("/eskatzailea/{id}", name="api_eskatzailea_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $eskatzailea = $this->getDoctrine()
            ->getRepository('AppBundle:Eskatzailea')
            ->findOneBy([
                'id' => $id,
            ]);

        if (!$eskatzailea) {
            throw $this->createNotFoundException('Ez da eskatzailerik aurkitu.');
        }

        $response = $this->createApiResponse($eskatzailea, 200);

        return $response;
    }

    /**
     * @Route("/eskatzailea", name="api_eskatzailea_list", options={"expose" = true} )
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
//        $izena = $request->query->get('izena');
        $criteria = $request->query->all();
        if ($criteria) {
            $eskatzaileak = $this->getDoctrine()
                ->getRepository('AppBundle:Eskatzailea')
                ->findAllLike($criteria);
        } else {
            $eskatzaileak = $this->getDoctrine()
                ->getRepository('AppBundle:Eskatzailea')
                ->findAll();
        }

        $response = $this->createApiResponse(['eskatzaileak' => $eskatzaileak], 200);

        return $response;
    }

//    /**
//     * @Route("/api/eskatzailea/{id}")
//     * @Method({"PUT", "PATCH"})
//     */
//    public function updateAction($id, Request $request)
//    {
//        $programmer = $this->getDoctrine()
//            ->getRepository('AppBundle:Eskatzailea')
//            ->findOneByNickname($nickname);
//
//        if (!$programmer) {
//            throw $this->createNotFoundException(sprintf(
//                'No programmer found with nickname "%s"',
//                $nickname
//            ));
//        }
//
//        $form = $this->createForm(new UpdateProgrammerType(), $programmer);
//        $this->processForm($request, $form);
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($programmer);
//        $em->flush();
//
//        $response = $this->createApiResponse($programmer, 200);
//
//        return $response;
//    }
//
//    /**
//     * @Route("/api/eskatzailea/{nickname}")
//     * @Method("DELETE")
//     */
//    public function deleteAction($nickname)
//    {
//        $programmer = $this->getDoctrine()
//            ->getRepository('AppBundle:Programmer')
//            ->findOneByNickname($nickname);
//
//        if ($programmer) {
//            // debated point: should we 404 on an unknown nickname?
//            // or should we just return a nice 204 in all cases?
//            // we're doing the latter
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($programmer);
//            $em->flush();
//        }
//
//        return new Response(null, 204);
//    }

    /**
     * @Route("/eskakizuna/{id}/erantzuna/new", name="api_erantzuna_new", options={"expose" = true})
     */
    public function newErantzunaAction(Request $request, $id)
    {
        //	$this->denyAccessUnlessGranted('ROLE_USER');
        //	$user = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository(Erabiltzailea::class)->findOneBy([
        'username' => 'admin',
    ]);
        $erantzuna = new Erantzuna();
        $form = $this->createForm(ErantzunaFormType::class, $erantzuna);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $eskakizuna = $this->getDoctrine()
            ->getRepository('AppBundle:Eskakizuna')
            ->findOneBy([
                'id' => $id,
            ]);

        if (null == $eskakizuna) {
            throw $this->createNotFoundException('Ez da ezkakizuna aurkitu beraz ezin da erantzuna gehitu.');
        }

        if (null != $eskakizuna) {
            $erantzuna->setEskakizuna($eskakizuna);
            $erantzuna->setNoiz(new DateTime());
            $erantzuna->setErantzulea($user);
            $em->persist($erantzuna);
            $em->flush();

            $response = $this->createApiResponse($erantzuna, 201);
            $erantzunaUrl = $this->generateUrl(
        'api_erantzuna_show',
        ['id' => $erantzuna->getId()]
        );
            $response->headers->set('Location', $erantzunaUrl);

            return $response;
        }
    }

    /**
     * @Route("/erantzuna/{$id}", name="api_erantzuna_show", options={"expose" = true})
     */
    public function showErantzunaAction(Request $request, $id)
    {
        $erantzuna = $this->getDoctrine()
            ->getRepository('AppBundle:Erantzuna')
            ->findOneBy([
                'id' => $id,
            ]);

        if (!$erantzuna) {
            throw $this->createNotFoundException('Ez da erantzunik aurkitu.');
        }

        $response = $this->createApiResponse($erantzuna, 200);

        return $response;
    }

    /**
     * @Route("/eskakizuna", name="api_eskakizuna_list", options={"expose" = true} )
     * @Method("GET")
     */
    public function listEskakizunaAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $authorization_checker = $this->get('security.authorization_checker');
        $bilatzaileaForm->handleRequest($request);

        if ($bilatzaileaForm->isSubmitted() && $bilatzaileaForm->isValid()) {
            $criteria = $bilatzaileaForm->getData();
            $criteria['role'] = null;
            if ($authorization_checker->isGranted('ROLE_KANPOKO_TEKNIKARIA')) {
                $criteria['enpresa'] = $user->getEnpresa();
            }
            if (array_key_exists('noiztik', $criteria)) {
                $from = $criteria['noiztik'];
                $criteria['noiztik'] = null;
            }
            if (array_key_exists('nora', $criteria)) {
                $to = $criteria['nora'];
                $criteria['nora'] = null;
            }
            $criteria_without_blanks = $this->_remove_blank_filters($criteria);
            if ($criteria_without_blanks || null != $from || null != $to) {
                $eskakizunak = $this->getDoctrine()
            ->getRepository(Eskakizuna::class)
            ->findAllFromTo($criteria_without_blanks, $from, $to);
            } else {
                $eskakizunak = $this->getDoctrine()
            ->getRepository(Eskakizuna::class)
            ->findAllOpen();
            }
            $response = $this->createApiResponse(['eskakizunak' => $eskakizunak], 200);

            return $response;
        }

        $em = $this->getDoctrine()->getManager();
        if ($authorization_checker->isGranted('ROLE_KANPOKO_TEKNIKARIA')) {
            $eskakizunak = $em->getRepository(Eskakizuna::class)->findBy([
            'enpresa' => $user->getEnpresa(),
            ]
        );
        } else {
            $eskakizunak = $this->getDoctrine()
            ->getRepository(Eskakizuna::class)
            ->findAllOpen();
        }

        $response = $this->createApiResponse(['eskakizunak' => $eskakizunak], 200);

        return $response;
    }

    /**
     * @Route("/batchclose", name="api_eskakizuna_batchclose")
     * @Method("POST")
     */
    public function closeAction(Request $request)
    {
        $ids = json_decode($request->get('ids'));

        $em = $this->getDoctrine()->getManager();

        foreach ($ids as $id) {
            $eskakizuna = $em->getRepository(Eskakizuna::class)->find($id);
            $eskakizuna->setItxieraData(new \DateTime());
            $egoera = $em->getRepository(Egoera::class)->find(Egoera::EGOERA_ITXIA);
            $eskakizuna->setEgoera($egoera);
            $em->persist($eskakizuna);
        }
        $em->flush();

        return $this->createApiResponse(
            ['Deskripzioa' => 'Eskakizun guztiak ondo itxi dira'], 200);
    }

    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        $clearMissing = 'PATCH' != $request->getMethod();
        $form->submit($data, $clearMissing);
    }

    private function _remove_blank_filters($criteria)
    {
        $new_criteria = [];
        foreach ($criteria as $key => $value) {
            if (!empty($value)) {
                $new_criteria[$key] = $value;
            }
        }

        return $new_criteria;
    }
}

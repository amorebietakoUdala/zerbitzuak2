<?php

namespace AppBundle\Controller\Web\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SaadTazi\GChartBundle\DataTable;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Estatistika;

/**
* @Route("/{_locale}/estatistika")
*/
class EstatistikaController extends Controller
{
     /**
     * @Route("/enpresako", name="admin_estatistika_enpresako")
     */
    public function estatistikaEnpresakoAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
	$authorization_checker = $this->get('security.authorization_checker');
	$connection = $this->getDoctrine()->getConnection(); // ...or getEntityManager() prior to Symfony 2.1
	$urteakstatement = $connection->prepare("SELECT distinct(urtea) FROM ESTATISTIKAK ORDER BY urtea DESC");
	$urteakstatement->execute();
	$urteakResultSet = $urteakstatement->fetchAll();
	foreach ($urteakResultSet as $key => $value) {
	    $urtea = $value['urtea'];
	    $urteak[$urtea] = $urtea;
	}
	$estatistikaForm = $this->createForm(EstatistikaFormType::class,null,[
	    'urteak' => $urteak
	]);

	$estatistikaForm->handleRequest($request);
	if ( $estatistikaForm->isSubmitted() && $estatistikaForm->isValid() ) {
	    $criteria = $estatistikaForm->getData();
	    $estatistikak = $this->getDoctrine()
		    ->getRepository(Estatistika::class)
		    ->findByCriteria($criteria);
	    $dataTable2 = $this->__createDataTable($estatistikak);
	    $guztira  = array_reduce($estatistikak, function ($i,$obj) {
		return $i += $obj->getEskakizunak();
	    });
	    return $this->render(
			'/estatistika/estatistika.html.twig', 
			array(
			    'dataTable2' => $dataTable2->toArray(),
			    'estatistikak' => $estatistikak,
			    'estatistikaForm' => $estatistikaForm->createView(),
			    'guztira' => $guztira
			)
		    );
	    
	}
	
	$em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
	$estatisticaRepository = $em->getRepository(Estatistika::class);
	$estatistikak = $estatisticaRepository->findBy([
	    'urtea' => date("Y")
	]);

	$dataTable2 = $this->__createDataTable($estatistikak);
	
	$guztira  = array_reduce($estatistikak, function ($i,$obj) {
	    return $i += $obj->getEskakizunak();
	});
        
        return $this->render(
                    '/estatistika/estatistika.html.twig', 
                    array(
                        'dataTable2' => $dataTable2->toArray(),
//			'cols' => $cols,
			'estatistikak' => $estatistikak,
			'estatistikaForm' => $estatistikaForm->createView(),
			'guztira' => $guztira
                    )
                );
        
    }
    
    private function __createDataTable($results) {
	$dataTable2 = new DataTable\DataTable();
        $dataTable2->addColumn('Enpresa', 'Enpresa', 'string');
//	$dataTable2->addColumnObject(new DataTable\DataColumn($results->getUrtea(), date("Y"), 'number'));
        
	$urteak = [];
	//test cells as array
	foreach ($results as $result) {
	    if (!array_key_exists($result->getUrtea(), $urteak)) {
		$urteak[$result->getUrtea()] = $result->getUrtea();
		$dataTable2->addColumnObject(new DataTable\DataColumn($result->getUrtea(), $result->getUrtea(), 'number'));
	    }
	    if ($result->getEnpresa() !== null ) {
		$dataTable2->addRow(array(
		    array('v' => $result->getEnpresa()->getIzena()),
		    array('v' => $result->getEskakizunak(), 'f' => $result->getEskakizunak().' eskakizun'),
		));
	    } else {
		$dataTable2->addRow(array(
		    array('v' => 'Zehaztu gabe'),
		    array('v' => $result->getEskakizunak(), 'f' => $result->getEskakizunak().' eskakizun'),
		));
	    }
	}
	return $dataTable2;
    }

}

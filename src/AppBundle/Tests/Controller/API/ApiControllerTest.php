<?php
namespace AppBundle\Tests\Controller\API;

use AppBundle\Test\ApiTestCase;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class ApiControllerTest extends ApiTestCase
{
    protected function setUp()
    {
        parent::setUp();
//	$data = array(
//	    'username' => 'admin',
//	    'plainPassword' => '666 666 666',
//            'email' => 'admin@gmail.com',
//            'izena' => 'admin',
//	);
//
//	$erabiltzailea = $this->createErabitzailea($data);
//	
//	$data = array(
//	    'eskatzailea_id' => $erabiltzailea 
//	    'jatorria', 
//	    enpresa_id, 
//	    zerbitzua_id, 
//	    egoera_id, 
//	    georeferentziazioa_id, 
//	    let, 
//	    mamia, 
//	    kalea, 
//	    noiz, 
//	    noizBidalia, 
//	    itxieraData, 
//	    argazkia, 
//	    eskakizunMota_id, 
//	    norkInformatu_id
//	    
//	    'eskatzailea' => 1,
//		'3', '4', NULL, '5', '2', NULL, '600867', 'Id perferendis asperiores et reprehenderit rem dolorum. Sed nisi aut accusamus aut. Recusandae omnis ex omnis est. Culpa dolorem dolor perspiciatis sit nostrum eum.', '6348 Mills Gateway Apt. 600', '2003-09-05 20:11:07', '2017-05-13 08:09:16', '2017-06-05 06:10:42', NULL, '2', '4'	    
//	    'plainPassword' => '666 666 666',
//            'email' => 'admin@gmail.com',
//            'izena' => 'admin',
//	);
//	$this->createEskakizuna($data);
	
    }

    
    public function testPOST()
    {
    	$data = array(
	    'izena' => 'eskatzailea1',
	    'telefonoa' => '666 666 666',
	    'nan' => '11111111H',
            'helbidea' => 'helbidea1',
            'emaila' => 'eskatzailea1@gmail.com',
            'herria' => 'amorebieta',
            'postaKodea' => '48889',
            'faxa' => '666 666 667',
            'aktibatua' => true,
	);

	// 1) Create a programmer resource
	$response = $this->client->post('/api/eskatzailea', [
	    'body' => json_encode($data)
	]);
	
	$this->responseToString($response);
	$this->assertEquals(201, $response->getStatusCode());
    	$finishedData = json_decode($response->getBody(),true);
        $this->assertStringEndsWith('/api/eskatzailea/'.$finishedData['id'],$response->getHeader('Location')[0]);
	$this->assertArrayHasKey('izena', $finishedData);
	$this->asserter()->assertResponsePropertyEquals($response,'izena','eskatzailea1');
	$this->asserter()->assertResponsePropertyEquals($response,'nan','11111111H');
        $this->asserter()->assertResponsePropertyEquals($response,'telefonoa','666 666 666');
        $this->asserter()->assertResponsePropertyEquals($response,'helbidea','helbidea1');
        $this->asserter()->assertResponsePropertyEquals($response,'emaila','eskatzailea1@gmail.com');
        $this->asserter()->assertResponsePropertyEquals($response,'herria','amorebieta');
        $this->asserter()->assertResponsePropertyEquals($response,'postaKodea','48889');
        $this->asserter()->assertResponsePropertyEquals($response,'faxa','666 666 667');
    }

    public function testPOSTErantzuna()
    {
	$data = array(
	    'erantzuna' => 'Eraaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaantzuna',
	    'erantzulea' => 1,
	    'eskakizuna' => 1,
	    'noiz' => new DateTime()
	    
	);

	// 1) Create a programmer resource
	$response = $this->client->post('/api/eskakizuna/1/erantzuna/new', [
	    'body' => json_encode($data),
	    'PHP_AUTH_USER' => 'admin',
	    'PHP_AUTH_PW'   => 'admin',
	]);
	
	$this->responseToString($response);
	$this->assertEquals(201, $response->getStatusCode());
    	$finishedData = json_decode($response->getBody(),true);
        $this->assertStringStartsWith('/api/eskakizuna/1/erantzuna'.$finishedData['id'],$response->getHeader('Location')[0]);
	$this->assertArrayHasKey('erantzuna', $finishedData);
	$this->assertArrayHasKey('noiz', $finishedData);
	$this->asserter()->assertResponsePropertyEquals($response,'erantzuna','Eraaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaantzuna');
    }

    
    
//    public function testGETEskatzailea()
//    {
//        $response = $this->client->get('/api/eskatzailea/');
//        $this->assertEquals(200, $response->getStatusCode());
//	
//        $this->asserter()->assertResponsePropertiesExist($response, array(
//	    'izena',
//	    'telefonoa',
//	    'nan',
//            'helbidea',
//            'emaila',
//            'herria',
//            'postaKodea',
//            'faxa',
//            'aktibatua',
//
//        ));
//        $this->asserter()->assertResponsePropertyEquals($response, 'izena', 'eskatzailea1');
//        $this->asserter()->assertResponsePropertyEquals(
//		$response, 
//		'_links.self', 
//		$this->adjustUri('/api/eskatzailea/eskatzailea1')
//	);
//    }

//    public function testGETProgrammerDeep()
//    {
//        $this->createProgrammer(array(
//            'nickname' => 'UnitTester',
//            'avatarNumber' => 3,
//        ));
//
//        $response = $this->client->get('/api/programmers/UnitTester?deep=1');
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->asserter()->assertResponsePropertiesExist($response, array(
//            'user.username'
//        ));
//    }
//
//    public function testGETProgrammersCollection()
//    {
//        $this->createEskatzailea([
//	    'izena' => 'eskatzailea1',
//	    'telefonoa' => '666 666 666',
//	    'nan' => '11111111H',
//            'helbidea' => 'helbidea1',
//            'emaila' => 'eskatzailea1@gmail.com',
//            'herria' => 'amorebieta',
//            'postaKodea' => '48889',
//            'faxa' => '666 666 667',
//            'aktibatua' => true,
//	]);
//        $this->createEskatzailea([
//	    'izena' => 'eskatzailea2',
//	    'telefonoa' => '666 666 667',
//	    'nan' => '11111112H',
//            'helbidea' => 'helbidea2',
//            'emaila' => 'eskatzailea2@gmail.com',
//            'herria' => 'amorebieta2',
//            'postaKodea' => '48880',
//            'faxa' => '666 666 668',
//            'aktibatua' => true,
//	]);
//
//        $response = $this->client->get('/api/eskatzailea');
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->asserter()->assertResponsePropertyIsArray($response, 'items');
//        $this->asserter()->assertResponsePropertyCount($response, 'items', 2);
//        $this->asserter()->assertResponsePropertyEquals($response, 'items[0].izena', 'eskatzailea1');
//        $this->asserter()->assertResponsePropertyEquals($response, 'items[1].izena', 'eskatzailea2');
//    }
//
//    public function testGETProgrammersCollectionPaginated()
//    {
//	$this->createProgrammer(array(
//	    'nickname' => 'willnotmatch',
//	    'avatarNumber' => 3,
//	));
//        for ($i = 0; $i < 25; $i++) {
//            $this->createProgrammer(array(
//                'nickname' => 'Programmer'.$i,
//                'avatarNumber' => 3,
//            ));
//        }
//
//        // page 1
//        $response = $this->client->get('/api/programmers?filter=programmer');
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->asserter()->assertResponsePropertyEquals(
//            $response,
//            'items[5].nickname',
//            'Programmer5'
//        );
//        $this->asserter()->assertResponsePropertyEquals(
//		$response, 
//		'count', 
//		10
//	);
//
//	$this->asserter()->assertResponsePropertyEquals(
//		$response, 
//		'total', 
//		25
//	);
//        $this->asserter()->assertResponsePropertyExists(
//		$response, 
//		'_links.next'
//	);
//
//        // page 2
//        $nextUrl = $this->asserter()->readResponseProperty(
//		$response, 
//		'_links.next'
//	);
//        $response = $this->client->get($nextUrl);
//        $this->assertEquals(200, $response->getStatusCode());
//	$this->debugResponse($response);
//        $this->asserter()->assertResponsePropertyEquals(
//            $response,
//            'items[5].nickname',
//            'Programmer15'
//        );
//        $this->asserter()->assertResponsePropertyEquals($response, 'count', 10);
//
//        $lastUrl = $this->asserter()->readResponseProperty($response, '_links.last');
//        $response = $this->client->get($lastUrl);
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->asserter()->assertResponsePropertyEquals(
//            $response,
//            'items[4].nickname',
//            'Programmer24'
//        );
//
//        $this->asserter()->assertResponsePropertyDoesNotExist($response, 'items[5].nickname');
//        $this->asserter()->assertResponsePropertyEquals($response, 'count', 5);
//    }
//
//    public function testPUTProgrammer()
//    {
//        $this->createProgrammer(array(
//            'nickname' => 'CowboyCoder',
//            'avatarNumber' => 5,
//	    'tagLine' => 'foo!'
//        ));
//
//	$data = array(
//	    'nickname' => 'CowgirlCoder',
//	    'avatarNumber' => 2,
//	    'tagLine' => 'foo!'
//	);
//	
//        $response = $this->client->put('/api/programmers/CowboyCoder', [
//            'body' => json_encode($data)
//        ]);
//        $this->assertEquals(200, $response->getStatusCode());
//        // the nickname is immutable on edit
//        $this->asserter()->assertResponsePropertyEquals($response, 'avatarNumber', 2);
//	$this->asserter()->assertResponsePropertyEquals($response, 'nickname', 'CowboyCoder');
//    }
//
//    public function testPATCHProgrammer()
//    {
//        $this->createProgrammer(array(
//            'nickname' => 'CowboyCoder',
//            'avatarNumber' => 5,
//            'tagLine' => 'foo',
//        ));
//
//        $data = array(
//            'tagLine' => 'bar',
//        );
//        $response = $this->client->patch('/api/programmers/CowboyCoder', [
//            'body' => json_encode($data)
//        ]);
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->asserter()->assertResponsePropertyEquals($response, 'avatarNumber', 5);
//        $this->asserter()->assertResponsePropertyEquals($response, 'tagLine', 'bar');
//    }
//
//    public function testDELETEProgrammer()
//    {
//        $this->createProgrammer(array(
//            'nickname' => 'UnitTester',
//            'avatarNumber' => 3,
//        ));
//
//        $response = $this->client->delete('/api/programmers/UnitTester');
//        $this->assertEquals(204, $response->getStatusCode());
//    }
//
//    public function testValidationErrors()
//    {
//        $data = array(
//            'avatarNumber' => 5,
//            'tagLine' => 'a test dev!'
//        );
//
//        // 1) Create a programmer resource
//        $response = $this->client->post('/api/programmers', [
//            'body' => json_encode($data)
//        ]);
//
//        $this->assertEquals(400, $response->getStatusCode());
//	$this->asserter()->assertResponsePropertiesExist($response, [
//	    'type',
//	    'title',
//	    'errors',
//	]);
//	$this->asserter()->assertResponsePropertyExists($response, 'errors.nickname');
//	$this->asserter()->assertResponsePropertyEquals(
//	    $response, 
//	    'errors.nickname[0]',
//	    'Please enter a clever nickname'
//	);
//	$this->asserter()->assertResponsePropertyDoesNotExist($response, 'errors.avatarNumber');
//	$this->assertEquals('application/problem+json', $response->getHeader('Content-Type')[0]);
//    }
//
//    public function testInvalidJson()
//    {
//        $invalidBody = <<<EOF
//{
//    "nickname": "JohnnyRobot",
//    "avatarNumber" : "2
//    "tagLine": "I'm from a test!"
//}
//EOF;
//
//        $response = $this->client->post('/api/programmers', [
//            'body' => $invalidBody
//        ]);
//
////	$this->debugResponse($response);
//        $this->assertEquals(400, $response->getStatusCode());
//        $this->asserter()->assertResponsePropertyContains(
//		$response, 
//		'type', 
//		'invalid_body_format'
//	);
//	
//    }
//
//    public function test404Exception()
//    {
//        $response = $this->client->get('/api/programmers/fake');
//
//        $this->assertEquals(404, $response->getStatusCode());
//        $this->assertEquals('application/problem+json', $response->getHeader('Content-Type')[0]);
//        $this->asserter()->assertResponsePropertyEquals(
//		$response, 
//		'type', 
//		'about:blank'
//	);
//        $this->asserter()->assertResponsePropertyEquals(
//		$response, 
//		'title', 
//		'Not Found'
//	);
//        $this->asserter()->assertResponsePropertyEquals(
//		$response, 
//		'detail', 
//		'No programmer found with nickname "fake"'
//	);
//    }
//    
//    public function testRequiresAuthentication() {
//	$data = array(
//            'avatarNumber' => 5,
//            'tagLine' => 'a test dev!'
//        );
//	
//	$response = $this->client->post('/api/programmers',[
//	    'body' => json_encode($data)
//	]);
//	$this->assertEquals(401,$response->getStatusCode());
//    }

    function responseToString (Response $response) {
	echo 'HTTP/'.$response->getProtocolVersion().' '.
		    $response->getStatusCode().' '.
		    $response->getReasonPhrase()."\n";
	echo 'Headers:';
	echo "\n";
	foreach( $response->getHeaders() as $clave=>$valor ) {
    //	echo $clave.': '.implode('\n',$valor);
	    foreach ($valor as $clave2=>$valor2)
		echo $clave.': '.$valor2."\n";
	}
	echo "Body:\n".$response->getBody()."\n";
    }

   
}

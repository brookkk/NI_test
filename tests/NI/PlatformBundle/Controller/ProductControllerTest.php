<?php

namespace tests\NI\PlatformBundle\Controller;

use NI\PlatformBundle\Entity\product as Product;
use NI\PlatformBundle\Form\productType as productType;
use NI\UserBundle\Entity\User;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;

use NI\PlatformBundle\Controller\ProductController;
use PHPUnit\Framework\TestCase;


class ProductControllerTest extends TestCase
{

    
   
  /*  public function testShowProductAction()
    {
        
        

        $pc = new ProductController();

        $result = $pc ->showProductAction();*/

        /*$this->execQuery($client, 'GET', null, '/api/comments');
    $response = $client->getResponse();
    $this->assertJsonResponse($response, 401);

     
     }*/


     public function testAuthAction()
     {
       /* $pc = new ProductController();

         
        $lien = 'http://localhost8012/NI_test/web/app_dev.php/api/auth';
        $postfields = array(
            'username' => 'Dr. Alberto Boyle I',
            'password' => 'secret',
        );

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $lien);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);

        $return = curl_exec($curl);
        curl_close($curl);
        $this->assertEquals(false, $return);*/
        


        /*$client = new \GuzzleHttp\Client('http://localhost:8012/NI_test/web/app_dev.php', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));*/

        $client = new \GuzzleHttp\Client([
            'base_url' => 'http://localhost:8012/NI_test/web/app_dev.php',
            'defaults' => array(
                'exceptions' => false
        )]);
        $data = array(
            "username" => "Dr. Alberto Boyle I",
            "password" => "secret"
        );
        $request = $client->post('/api/auth', null, json_encode($data));
        $response = $request->send();

        print_r( $response->getStatusCode());

        $this->assertEquals(201, $response->getStatusCode());

     }
    


    


}

<?php

namespace NI\PlatformBundle\Controller;

use NI\PlatformBundle\Entity\product as Product;
use NI\PlatformBundle\Form\productType as productType;
use NI\UserBundle\Entity\User;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;


class ProductController extends Controller
{

    
   /**
     * @Get(
     *     path = "/product/{id}",
     *     name = "NI_product_show",
     *     requirements = {"id"="\d+"}
     * )
     */
    public function showProductAction()
    {
        //return "toto";
        return $this->json("toto");
    }


    /**
     * @Rest\Post("/product")
     */
    public function createAction(Request $request/*Product $product*/)
    {

        $prdt = $this->get('serializer')->serialize($request->query, 'json');

        $data = $this->get('serializer')->deserialize($prdt, 'NI\PlatformBundle\Entity\product', 'json');
        
        $em= $this  ->getDoctrine()  ->getManager();
        $repository = $em  ->getRepository('NIUserBundle:User');
        $user =  $repository->findBy([
            'id' => 1 ,
          ]);

         // print_r($user[0]);

        //$user = new User;
        //$user->setId(1);
        print_r($data);
        $product = new Product;
        $product->setSku($data->getSku());
        $product->setName($data->getName());
        $product->setUser($user[0]);
        //$form = $this->get('form.factory')->create(productType::class, $product);
        //$form->submit($data);
        
        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();




        /*$em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();*/

        return $this->json("product saved");
        //dump($product); die;
    }


}

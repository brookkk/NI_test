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
use FOS\UserBundle\Model\UserInterface;

class ProductController extends Controller
{

    
   /**
     * @Get(
     *     path = "/products",
     *     name = "NI_products_show",
     * )
     */
    public function showProductAction()
    {
        


        $listProducts= $this->getDoctrine()->getRepository('NIPlatformBundle:product')->findAll();

        
         

        $json_products = array();
        

        //print_r($listProducts);
        $json_products = [];

        foreach($listProducts as $product){
            //print_r($product);
            //print_r(json_encode($product));
            //print_r($this->productToJson($product));
            $json_products[] =$this->productToJson($product);
        }

         

         return $json_products;
         //return "toto";
    }



    /**
     * @Get(
     *     path = "/user",
     *     name = "NI_user_show",
     * )
     */
    public function showUserNameAction()
    {
        


        return $this->json($this->getUser()->getUsername());
        //return json("testsss");
    }

    public function productToJson($product)
    {
        /*$user = $this->getDoctrine()->getRepository('NIUserBundle:User')->findBy([
            'id' => $product->getUser() ,
          ]);*/
            $json =  array(
                'id' => $product->getId(),
                'sku' => $product -> getSku(),
                'name' => $product->getName(),
                //'user' => $user[0]->getUsername()
            );
        
        return $json;
    }


    /**
     * @Rest\Post("/user/product")
     */
    public function createAction(Request $request)
    {

        //$user = $this->get('security.token_storage')->getToken();
        $user = $this -> getUser();

        $prdt = $this->get('serializer')->serialize($request->query, 'json');

        $data = $this->get('serializer')->deserialize($prdt, 'NI\PlatformBundle\Entity\product', 'json');
        
        $em= $this  ->getDoctrine()  ->getManager();
        $repository = $em  ->getRepository('NIUserBundle:User');
        /*$user =  $repository->findBy([
            'id' => 1 ,
          ]);*/

         // print_r($user[0]);

        //$user = new User;
        //$user->setId(1);
        //print_r($data);
        $product = new Product;
        $product->setSku($data->getSku());
        $product->setName($data->getName());
        $product->addUser($user);
        //$form = $this->get('form.factory')->create(productType::class, $product);
        //$form->submit($data);
        
        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();




 
 
        return $this->json("product saved");
        //dump($product); die;
    }



    /**
     * @Rest\Post("/auth")
     * @Rest\View(StatusCode = 201)
     */
    public function authAction(Request $request)
    {
        return $this->redirectToRoute('api_login_check', array('request'=>$request));
    }


    /**
     * @Rest\Post("/auth2")
     * @Rest\View(StatusCode = 201)
     */
    public function authenticateLoginAction(Request $request)
    {
        $user = $this->get('serializer')->serialize($request->query, 'json');
        $user_array = json_decode($user, true);
        //echo($user_array['username']);

        //Méthode qui vérifie que l'utilisateur a les bons idntifiants
        //en input : $user de type User ayant un "username" et "password"
        //en output : si "good credentials" : un 'token' === l'heure actuelle transformée en un entier ( => unique)
        // + l'objet "user" trouvé au niveau de la BD
        // sinon "user non trouvé" ou "mdp erroné"
 
        $factory = $this->get('security.encoder_factory');

        // pour vérifier si le user existe au niveau de la BD
        $found = false;

        // pour vérifier si le psw est correct pour le user
        $good_psw=false;

        // on vérifie l'existance d'un user avec l'identifiant "username"
        $bd_user = $this->get('fos_user.user_manager')->findUserByUsername($user_array['username']);

        //print_r($bd_user);

        if($bd_user)
        {
            //on a trouvé un user avec l'idntifiant "username"
            $found = true;
            //on va encoder le psw de user (de l'input) et vérifier les deux psw encodés
            $encoder = $factory->getEncoder($bd_user);
            $pass=$user_array['password'];
            $good_psw = $encoder->isPasswordValid($bd_user->getPassword(),$pass,$bd_user->getSalt());

        }

        //on crée le token à partir de DateTime()
        $date = new \DateTime();
        $token = $date->format('YmdHis');
 
        if(!$found)
            return "user : not found";
        else if(!$good_psw)
            return "wrong password";
        else{
            $bd_user->setLastLogin($date);
             //l'objet retour est le "token" + "user trouvé"
        $retour = array('token'=> $token, 'user'=> array('username'=>$bd_user->getUsername(), 'email' => $bd_user->getEmail(),
         'id'=>$bd_user->getId() ));
            return $retour;}

            //return $this->json("good");
 
    }


}

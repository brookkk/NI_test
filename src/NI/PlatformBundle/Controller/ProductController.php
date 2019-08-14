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

        $json_products = [];

        foreach($listProducts as $product){
            $json_products[] =$this->productToJson($product);
        }
         return $json_products;
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
    }


    /**
     * @Get(
     *     path = "/user/products",
     *     name = "NI_user_show_products",
     * )
     */
    public function showUserProductsAction()
    {
        $user = $this->getUser();

       
        return $this->json($user->getProducts());
    }

    public function productToJson($product)
    {
        
            $json =  array(
                'sku' => $product -> getSku(),
                'name' => $product->getName(),
            );
        
        return $json;
    }


    /**
     * @Rest\Post("/user/product/{sku}")
     */
    public function createAction($sku)
    {

        $user = $this -> getUser();

        $em= $this  ->getDoctrine()  ->getManager();
        
        $product= $this->getDoctrine()->getRepository('NIPlatformBundle:product')->findBy([
            'sku' => $sku
        ]);


        if(!$product){
            return "404 Error: product does not exist";
        }
        else {

        if($user->getProducts()->contains($product[0])){
            return "product already attached";
        }
        else
        {$user->addProduct($product[0]);
        
        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();
 
        return $this->json("product ". $product[0]->getSku()." attached");}
    }
     }


     /**
     * @Rest\Delete("/user/product/{sku}")
     * * @Rest\View(StatusCode = 201)
     */
    public function detachProductFromUserAction($sku)
    {
        $product= $this->getDoctrine()->getRepository('NIPlatformBundle:product')->findBy([
            'sku' => $sku
        ]);

        if(!$product){
            return "product does not exist";
        }
        else{
        $user = $this->getUser();


        if(!$user->getProducts()->contains($product[0])){
            return "product already detached";
        }
        else{

        $user->removeProduct($product[0]);

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        return "product detached";
        }
        }
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
     * @Rest\Put("/import_database")
     * @Rest\View(StatusCode = 201)
     */

     public function importDatabaseAction()
     {

        //importing users
        
        // we start by reading the "imports/users file
        $users_file = fopen("imports/users", "r") or die("Unable to open users file!");
        $users = array ();
        while(!feof($users_file)) {
            // a temp array to store the line that we get from the file
            $temp_array = explode(",",fgets($users_file));
            $users[] = [
                'id' => $temp_array[0],
                'name' => $temp_array[1],
                'email' => $temp_array[2],
                'password' => trim($temp_array[3]),
            ];
          }

        // $users is an array of this form  user ('id'=> id, 'name'=> name, 'email'=>email, 'password'=> psw)  
        // we go through the users array to import them into the database by creating $bd_users objects and initialazing them
        // for reasons of simplicity (simplifying import in mass), we use plain password, and mock the field "salt"  
        foreach ($users as $user )
          {
              $bd_user = new User();
              $bd_user ->setId($user['id']);
              $bd_user ->setUsername($user['name']);
              $bd_user ->setEmail($user['email']);
              $bd_user->setSalt("0tETSwKIch/ZJIb0wGaiYshUQsM.9ZipsfPsBMyXGfI");
              $bd_user ->setPassword(trim($user['password'])."{0tETSwKIch/ZJIb0wGaiYshUQsM.9ZipsfPsBMyXGfI}");
              $bd_user ->setEnabled(true);

              $em = $this->getDoctrine()->getManager();
              $em->persist($bd_user);
              $em->flush();
          }
          





        
        //importing products
        
        // reading the "imports/products file
        $products_file = fopen("imports/products", "r") or die("Unable to open users file!");
        $products = array ();
        while(!feof($products_file)) {
            // a temp array to store the line that we get from the file
            $temp_array = explode(",",fgets($products_file));
            $products[] = [
                'sku' => $temp_array[0],
                'name' => trim($temp_array[1]),
            ];
          }

        // $products : array : ('sku'=> sku, 'name'=> name)  
        // we go through the products array to import them into the database by creating $bd_products objects and initialazing them
        
        foreach ($products as $product )
          {
              $bd_product = new product();
              $bd_product ->setSku($product['sku']);
              $bd_product ->setName($product['name']);
              

              $em = $this->getDoctrine()->getManager();
              $em->persist($bd_product);
              $em->flush();
          }

          



          //importing products
        
        // we start by reading the "imports/users file
        $history_file = fopen("imports/history", "r") or die("Unable to open users file!");
        $histories = array ();
        while(!feof($history_file)) {
            // a temp array to store the line that we get from the file
            $temp_array = explode(",",fgets($history_file));
            

            $histories[] = [
                'user_id' => $temp_array[0],
                'product_sku' => $temp_array[1],
            ];
            
          }

        // $users is an array of this form  user ('id'=> id, 'name'=> name, 'email'=>email, 'password'=> psw)  
        // we go through the users array to import them into the database by creating $bd_users objects and initialazing them
        // for reasons of simplicity (simplifying import in mass), we use plain password, and mock the field "salt"  
        foreach ($histories as $history )
        {
              $user= $this->getDoctrine()->getRepository('NIUserBundle:User')->findBy([
                //'id' => $history['user_id']
                'id' => $history['user_id']
            ]);
            $product= $this->getDoctrine()->getRepository('NIPlatformBundle:product')->findBy([
                'sku' => trim($history['product_sku'])
            ]);
            $bd_user = $user[0];
            $bd_product = $product[0];
            $bd_user->addProduct($bd_product);  
              

              $em = $this->getDoctrine()->getManager();
              $em->persist($bd_user);
              $em->flush();
          }
      

        return "users, products and purchase history imported";
     }


    


}

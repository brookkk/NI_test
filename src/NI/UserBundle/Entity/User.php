<?php

namespace NI\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser; 
use Symfony\Component\Validator\Constraints as Assert;
use NI\PlatformBundle\Entity\Product as Product;

 


/**
 * @ORM\Table(name="ni_user")
 * @ORM\Entity(repositoryClass="NI\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{

 

    /**
     * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

  
  /**
     * @ORM\ManyToMany(targetEntity="NI\PlatformBundle\Entity\product", cascade={"persist"})
     */
    private $products;


    /**
     * Get the value of user
     */ 
    public function getProducts()
    {
        return $this->products;
    }

    public function addProduct(Product $product)
  {
    $this->products[] = $product;

    return $this;
  }

  public function removeProduct(Product $product)
  {
    $this->products->removeElement($product);
  }

  public function __construct()
  {
    $this->products = new ArrayCollection();
  }


    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}


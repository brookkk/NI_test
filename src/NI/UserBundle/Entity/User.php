<?php

namespace NI\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser; 
use Symfony\Component\Validator\Constraints as Assert;
 


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


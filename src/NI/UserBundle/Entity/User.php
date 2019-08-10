<?php

namespace NI\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser; 
use Symfony\Component\Validator\Constraints as Assert;
 


/**
 * @ORM\Table(name="NI_user")
 * @ORM\Entity(repositoryClass="Lit\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{

 

    /**
     * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

  


}


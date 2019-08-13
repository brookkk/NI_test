<?php

namespace NI\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NI\UserBundle\Entity\User as User;

/**
 * product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="NI\PlatformBundle\Repository\productRepository")
 */
class product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sku", type="string", length=255)
     */
    private $sku;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="NI\UserBundle\Entity\User", cascade={"persist"})
     */
    private $user;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sku
     *
     * @param string $sku
     *
     * @return product
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}


<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Client
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isAuthorized", type="boolean")
     */
    private $isAuthorized;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isAuthorized
     *
     * @param boolean $isAuthorized
     * @return Client
     */
    public function setIsAuthorized($isAuthorized)
    {
        $this->isAuthorized = $isAuthorized;

        return $this;
    }

    /**
     * Get isAuthorized
     *
     * @return boolean 
     */
    public function getIsAuthorized()
    {
        return $this->isAuthorized;
    }
}

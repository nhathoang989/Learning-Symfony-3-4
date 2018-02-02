<?php

namespace OgilvyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoginLog
 *
 * @ORM\Table(name="login_log")
 * @ORM\Entity(repositoryClass="OgilvyBundle\Repository\LoginLogRepository")
 */
class LoginLog
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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var int
     *
     * @ORM\Column(name="fail_total", type="smallint")
     */
    private $failTotal;

    /**
     * @var int
     *
     * @ORM\Column(name="updated_at", type="integer")
     */
    private $updatedAt;


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
     * Set username
     *
     * @param string $username
     * @return LoginLog
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set failTotal
     *
     * @param integer $failTotal
     * @return LoginLog
     */
    public function setFailTotal($failTotal)
    {
        $this->failTotal = $failTotal;

        return $this;
    }

    /**
     * Get failTotal
     *
     * @return integer 
     */
    public function getFailTotal()
    {
        return $this->failTotal;
    }

    /**
     * Set updatedAt
     *
     * @param integer $updatedAt
     * @return LoginLog
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return integer 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

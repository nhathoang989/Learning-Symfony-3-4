<?php

namespace OgilvyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * AdminUser
 *
 * @ORM\Table(name="admin_user")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\AdminUserRepository")
 */
class AdminUser implements UserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=150, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=255, nullable=true)
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=150)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role_list", type="string", length=255, nullable=true)
     */
    private $roleList;

    /**
     * @var int
     *
     * @ORM\Column(name="created_at", type="integer", nullable=true)
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;


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
     * @return AdminUser
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
     * Set displayName
     *
     * @param string $displayName
     * @return AdminUser
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return AdminUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return AdminUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set roleList
     *
     * @param string $roleList
     * @return AdminUser
     */
    public function setRoleList($roleList)
    {
        $this->roleList = $roleList;

        return $this;
    }

    /**
     * Get roleList
     *
     * @return string
     */
    public function getRoleList()
    {
        return $this->roleList;
    }

    /**
     * Set createdAt
     *
     * @param integer $createdAt
     * @return AdminUser
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return integer
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return AdminUser
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
        return serialize(
            array(
                $this->id,
                $this->username,
                $this->password,
                // see section on salt below
                // $this->salt,
            )
        );
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
        $roles = array();
        if ($this->id == 1) {
            $roles[] = 'ROLE_SUPER_ADMIN';
        }

        if ($this->roleList && $this->roleList != '') {
            $arr = explode(';', $this->roleList);
            foreach ($arr as $value) {
                $value = trim($value);
                if ($value) {
                    $roles[] = $value;
                }
            }
        }
        /*if($this->roleList != '') {
            return json_decode($this->roleList);
        }*/

        /*try {
            return json_decode($this->roleList);
        } catch(Exception $e) {
            return array();
        }*/

        return $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}

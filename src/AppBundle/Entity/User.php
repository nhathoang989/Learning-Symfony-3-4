<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMSSerializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(name="phone_number", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(message="Please enter your phone number.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=7,
     *     max=25,
     *     minMessage="The phoneNumber is too short.",
     *     maxMessage="The phoneNumber is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $phoneNumber;

    /**
     * @return mixed
     */
    public function getPhoneNumber ()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber ($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }
}

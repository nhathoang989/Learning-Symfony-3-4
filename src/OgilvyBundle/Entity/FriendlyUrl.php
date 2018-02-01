<?php

namespace OgilvyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FriendlyUrl
 *
 * @ORM\Table(name="friendly_url")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\FriendlyUrlRepository")
 */
class FriendlyUrl
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
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="langcode", type="string", length=255)
     */
    private $langcode;


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
     * Set source
     *
     * @param string $source
     * @return FriendlyUrl
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return FriendlyUrl
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set langcode
     *
     * @param string $langcode
     * @return FriendlyUrl
     */
    public function setLangcode($langcode)
    {
        $this->langcode = $langcode;

        return $this;
    }

    /**
     * Get langcode
     *
     * @return string 
     */
    public function getLangcode()
    {
        return $this->langcode;
    }
}

<?php

namespace OgilvyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageMeta
 *
 * @ORM\Table(name="page_meta")
 * @ORM\Entity(repositoryClass="OgilvyBundle\Repository\PageMetaRepository")
 */
class PageMeta
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
     * @var int
     *
     * @ORM\Column(name="page_id", type="integer")
     */
    private $pageId;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_code", type="string", length=255)
     */
    private $metaCode;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_value", type="text", nullable=true)
     */
    private $metaValue;


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
     * Set pageId
     *
     * @param integer $pageId
     * @return PageMeta
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    /**
     * Get pageId
     *
     * @return integer 
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Set metaCode
     *
     * @param string $metaCode
     * @return PageMeta
     */
    public function setMetaCode($metaCode)
    {
        $this->metaCode = $metaCode;

        return $this;
    }

    /**
     * Get metaCode
     *
     * @return string 
     */
    public function getMetaCode()
    {
        return $this->metaCode;
    }

    /**
     * Set metaValue
     *
     * @param string $metaValue
     * @return PageMeta
     */
    public function setMetaValue($metaValue)
    {
        $this->metaValue = $metaValue;

        return $this;
    }

    /**
     * Get metaValue
     *
     * @return string 
     */
    public function getMetaValue()
    {
        return $this->metaValue;
    }
}

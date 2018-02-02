<?php

namespace OgilvyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuLink
 *
 * @ORM\Table(name="menu_link")
 * @ORM\Entity(repositoryClass="OgilvyBundle\Repository\MenuLinkRepository")
 */
class MenuLink
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
     * @ORM\Column(name="parent_link_id", type="integer")
     */
    private $parentLinkId;

    /**
     * @var int
     *
     * @ORM\Column(name="menu_id", type="integer")
     */
    private $menuId;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="friendly_path", type="string", length=255, nullable=true)
     */
    private $friendlyPath;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="smallint")
     */
    private $weight;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
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
     * Set parentLinkId
     *
     * @param integer $parentLinkId
     * @return MenuLink
     */
    public function setParentLinkId($parentLinkId)
    {
        $this->parentLinkId = $parentLinkId;

        return $this;
    }

    /**
     * Get parentLinkId
     *
     * @return integer 
     */
    public function getParentLinkId()
    {
        return $this->parentLinkId;
    }

    /**
     * Set menuId
     *
     * @param integer $menuId
     * @return MenuLink
     */
    public function setMenuId($menuId)
    {
        $this->menuId = $menuId;

        return $this;
    }

    /**
     * Get menuId
     *
     * @return integer 
     */
    public function getMenuId()
    {
        return $this->menuId;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return MenuLink
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return MenuLink
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return MenuLink
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set friendlyPath
     *
     * @param string $friendlyPath
     * @return MenuLink
     */
    public function setFriendlyPath($friendlyPath)
    {
        $this->friendlyPath = $friendlyPath;

        return $this;
    }

    /**
     * Get friendlyPath
     *
     * @return string 
     */
    public function getFriendlyPath()
    {
        return $this->friendlyPath;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return MenuLink
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return MenuLink
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
}

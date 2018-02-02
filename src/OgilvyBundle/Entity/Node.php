<?php

namespace OgilvyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Node
 *
 * @ORM\Table(name="node")
 * @ORM\Entity(repositoryClass="OgilvyBundle\Repository\NodeRepository")
 */
class Node
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content_type", type="string", length=255)
     */
    private $contentType;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=500, nullable=true)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;

    /**
     * @var int
     *
     * @ORM\Column(name="created_by", type="integer", nullable=true)
     */
    private $createdBy;

    /**
     * @var int
     *
     * @ORM\Column(name="updated_by", type="integer", nullable=true)
     */
    private $updatedBy;

    /**
     * @var int
     *
     * @ORM\Column(name="created_at", type="integer")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="updated_at", type="integer")
     */
    private $updatedAt;

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
     * Set title
     *
     * @param string $title
     * @return Node
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
     * Set contentType
     *
     * @param string $contentType
     * @return Node
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Node
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Node
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return Node
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updatedBy
     *
     * @param integer $updatedBy
     * @return Node
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return integer 
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set createdAt
     *
     * @param integer $createdAt
     * @return Node
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
     * Set updatedAt
     *
     * @param integer $updatedAt
     * @return Node
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

    /**
     * Set status
     *
     * @param integer $status
     * @return Node
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


    private $friendlyUrl = '';

    public function setFriendlyUrl($friendlyUrl)
    {
        return $this->friendlyUrl = $friendlyUrl;
    }

    public function getFriendlyUrl()
    {

        return $this->friendlyUrl;
    }
}

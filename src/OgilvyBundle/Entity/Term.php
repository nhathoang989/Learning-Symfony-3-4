<?php

namespace OgilvyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Term
 *
 * @ORM\Table(name="term")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\TermRepository")
 */
class Term
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="taxonomy_code", type="string", length=255)
     */
    private $taxonomyCode;

    /**
     * @var int
     *
     * @ORM\Column(name="parent_term_id", type="integer", nullable=true)
     */
    private $parentTermId;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=500, nullable=true)
     */
    private $summary;

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
     * @var weight
     *
     * @ORM\Column(name="weight", type="integer")
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
     * Set name
     *
     * @param string $name
     * @return Term
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
     * Set taxonomyCode
     *
     * @param string $taxonomyCode
     * @return Term
     */
    public function setTaxonomyCode($taxonomyCode)
    {
        $this->taxonomyCode = $taxonomyCode;

        return $this;
    }

    /**
     * Get taxonomyCode
     *
     * @return string
     */
    public function getTaxonomyCode()
    {
        return $this->taxonomyCode;
    }

    /**
     * Set parentTermId
     *
     * @param integer $parentTermId
     * @return Term
     */
    public function setParentTermId($parentTermId)
    {
        $this->parentTermId = $parentTermId;

        return $this;
    }

    /**
     * Get parentTermId
     *
     * @return integer 
     */
    public function getParentTermId()
    {
        return $this->parentTermId;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Term
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
     * Set createdAt
     *
     * @param integer $createdAt
     * @return Term
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
     * @return Term
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
     * Set weight
     *
     * @param integer $weight
     * @return Term
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
     * @return Term
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

<?php

namespace OgilvyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NodeTerm
 *
 * @ORM\Table(name="node_term")
 * @ORM\Entity(repositoryClass="OgilvyBundle\Repository\NodeTermRepository")
 */
class NodeTerm
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
     * @ORM\Column(name="term_id", type="integer")
     */
    private $termId;

    /**
     * @var int
     *
     * @ORM\Column(name="node_id", type="integer")
     */
    private $nodeId;

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
     * Set termId
     *
     * @param integer $termId
     * @return NodeTerm
     */
    public function setTermId($termId)
    {
        $this->termId = $termId;

        return $this;
    }

    /**
     * Get termId
     *
     * @return integer 
     */
    public function getTermId()
    {
        return $this->termId;
    }

    /**
     * Set nodeId
     *
     * @param integer $nodeId
     * @return NodeTerm
     */
    public function setNodeId($nodeId)
    {
        $this->nodeId = $nodeId;

        return $this;
    }

    /**
     * Get nodeId
     *
     * @return integer 
     */
    public function getNodeId()
    {
        return $this->nodeId;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return NodeTerm
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

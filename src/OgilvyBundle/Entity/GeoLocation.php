<?php

namespace OgilvyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GeoLocation
 *
 * @ORM\Table(name="geo_location")
 * @ORM\Entity(repositoryClass="OgilvyBundle\Repository\GeoLocationRepository")
 */
class GeoLocation
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
   * @ORM\Column(name="entity_id", type="integer")
   */
  private $entityId;

  /**
   * @var string
   *
   * @ORM\Column(name="site", type="string", length=100)
   */
  private $site;

  /**
   * @var string
   *
   * @ORM\Column(name="type", type="string", length=100)
   */
  private $type;

  /**
   * @var string
   *
   * @ORM\Column(name="latitude", type="string", length=50, nullable=true)
   */
  private $latitude;

  /**
   * @var string
   *
   * @ORM\Column(name="longitude", type="string", length=50, nullable=true)
   */
  private $longitude;

  /**
   * @var string
   *
   * @ORM\Column(name="description", type="text", nullable=true)
   */
  private $description;


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
   * Set entityId
   *
   * @param integer $entityId
   *
   * @return GeoLocation
   */
  public function setEntityId($entityId)
  {
    $this->entityId = $entityId;

    return $this;
  }

  /**
   * Get entityId
   *
   * @return integer
   */
  public function getEntityId()
  {
    return $this->entityId;
  }

  /**
   * Set site
   *
   * @param string $site
   *
   * @return GeoLocation
   */
  public function setSite($site)
  {
    $this->site = $site;

    return $this;
  }

  /**
   * Get site
   *
   * @return string
   */
  public function getSite()
  {
    return $this->site;
  }

  /**
   * Set type
   *
   * @param string $type
   *
   * @return GeoLocation
   */
  public function setType($type)
  {
    $this->type = $type;

    return $this;
  }

  /**
   * Get type
   *
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * Set latitude
   *
   * @param string $latitude
   *
   * @return GeoLocation
   */
  public function setLatitude($latitude)
  {
    $this->latitude = $latitude;

    return $this;
  }

  /**
   * Get latitude
   *
   * @return string
   */
  public function getLatitude()
  {
    return $this->latitude;
  }

  /**
   * Set longitude
   *
   * @param string $longitude
   *
   * @return GeoLocation
   */
  public function setLongitude($longitude)
  {
    $this->longitude = $longitude;

    return $this;
  }

  /**
   * Get longitude
   *
   * @return string
   */
  public function getLongitude()
  {
    return $this->longitude;
  }

  /**
   * Set description
   *
   * @param string $description
   *
   * @return GeoLocation
   */
  public function setDescription($description)
  {
    $this->description = $description;

    return $this;
  }

  /**
   * Get description
   *
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }
}

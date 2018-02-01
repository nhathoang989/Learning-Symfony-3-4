<?php

namespace OgilvyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gregwar\Image\Image;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\FileRepository")
 */
class File
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
     * @ORM\Column(name="file_path", type="string", length=255)
     */
    private $filePath;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=100)
     */
    private $mimeType;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

    /**
     * @var int
     *
     * @ORM\Column(name="created_by", type="integer")
     */
    private $createdBy;

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
     * Set filePath
     *
     * @param string $filePath
     * @return File
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return File
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
     * Set mimeType
     *
     * @param string $mimeType
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return File
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
     * Set status
     *
     * @param integer $status
     * @return File
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

    public function getFullPath($root = '')
    {
        return $root.'/web/uploads/files/'.$this->getFilePath();
    }

    public function getFilesDir()
    {
        return 'web/uploads/files';
    }

    public function getThumbsDir()
    {
        return $this->getFilesDir().'/thumbs';
    }

    public function upload($root, $fileObj)
    {
        $descPath = $root.'/'.$this->getFilesDir().'/';
        //$filename = md5(time()).'_'.$fileObj->getClientOriginalName();
        $filename = $fileObj->getClientOriginalName();
        //$tmp = new UploadedFile();
        //$folder = $filename[0] . '/' . $filename[1];
        $folder = date('Y/m/d', time());//$filename[0] . '/' . $filename[1];
        $fs = new Filesystem();
        try {

            $fs->mkdir($descPath.$folder, 01777);
            if (!file_exists($descPath.$folder.'/'.$filename)) {
                $newFile = $fileObj->move($descPath.$folder, $filename);
            } else {
                $filename = time().'-'.$filename;
                $newFile = $fileObj->move($descPath.$folder, $filename);
            }
            $this->setType($newFile->getExtension());
            $this->setMimeType($fileObj->getClientMimeType());
            $this->setSize($fileObj->getClientSize());
            //$this->resizeSmall($root, $filename);
            //$this->resizeBig($root, $filename);

        } catch (IOExceptionInterface $e) {
            throw new Exception("An error occurred while creating your directory at ".$e->getPath());
        }

        return $this->setFilePath($folder.'/'.$filename);
    }

    public function resize($width, $height)
    {
        $id = $this->getId();
        $kernel = $GLOBALS['kernel'];
        $root = $kernel->getRootDir().'/..';
        if ($this->getFilePath() && file_exists($this->getFullPath($root))) {
            if (!file_exists($this->getThumbsDir().'/'.$id.'/'.$width.'x'.$height.'/'.$this->getFilePath())) {
                $fs = new Filesystem();
                $parts = explode('/', $this->getFilePath());
                $fs->mkdir($root.'/'.$this->getThumbsDir().'/'.$id.'/'.$width.'x'.$height.'/'.$parts[0].'/'.$parts[1], 01777);
                Image::open($root.'/'.$this->getFilesDir().'/'.$this->getFilePath())
//                  ->cropResize($width, $height)
                  ->zoomCrop($width, $height)
                  ->save($root.'/'.$this->getThumbsDir().'/'.$id.'/'.$width.'x'.$height.'/'.$this->getFilePath(), 'guess', 100);
            }
            if(isset($GLOBALS['_SERVER']['BASE'])) {
                $subFolder = $GLOBALS['_SERVER']['BASE'];
            } else {
                $subFolder = '';
            }

            return $subFolder.'/'.$this->getThumbsDir().'/'.$id.'/'.$width.'x'.$height.'/'.$this->getFilePath();
        }

        return false;
    }
}

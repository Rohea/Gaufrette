<?php
namespace Gaufrette;

/**
 * Points to a file in a filesystem
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class File
{
    protected $key;

    /**
     * @var content
     */
    protected $content = null;

    /**
     * @var array metadata in associative array. Only for adapters that support metadata
     */
    protected $metadata = array();

    /**
     * Human readable filename (usually the end of the key)
     * @var string name
     */
    protected $name = null;

    /**
     * File size in bytes
     * @var int size
     */
    protected $size = 0;

    /**
     * File date modified
     * @var int mtime
     */
    protected $mtime = null;

    /**
     * Constructor
     *
     * @param string     $key
     */
    public function __construct($key)
    {
        $this->key = $key;
        $this->name = $key;
    }

    /**
     * Returns the key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns the content
     *
     * @throws Gaufrette\Exception\FileNotFound
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string name of the file
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return int size of the file
     */
    public function getSize()
    {
        if ($this->size) {
            return $this->size;
        }

        try {
            return $this->size = Util\Size::fromContent($this->getContent());
        } catch (FileNotFound $exception) {
        }

        return 0;
    }

    /**
     * Returns the file modified time
     *
     * @return int
     */
    public function getMtime()
    {
        return $this->mtime;
    }

    /**
     * Get single metadata item
     *
     * @param string metaKey
     *
     * @return string value, null if key does not exist
     */
    public function getMetadataItem($metaKey)
    {
        if (isset($this->metadata[$metaKey])) {
            return $this->metadata[$metaKey];
        }
        return null;
    }

    /**
     * @param int size of the file
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Sets the content
     *
     * @param string $content
     * @param array  $metadata optional metadata which should be send when write
     *
     * @return integer The number of bytes that were written into the file, or
     *                 FALSE on failure
     */
    public function setContent($content, $metadata = array())
    {
        $this->content = $content;
        $this->setMetadata($metadata);

        return $this->size = Util\Size::fromContent($content);
    }

    /**
     * @param string name of the file
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @deprecated Use setMetadataItem($item) instead
     *
     * @param array $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @param integer $mtime
     */
    public function setMTime($mtime)
    {
        $this->mtime = $mtime;
    }

    /**
     * Add one metadata item to file (only if adapter supports metadata)
     *
     * @param   string  $metaKey
     * @param   string  $metaValue
     */
    public function setMetadataItem($metaKey, $metaValue)
    {
        $this->metadata[$metaKey] = $metaValue;
    }

}

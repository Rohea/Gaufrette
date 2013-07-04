<?php

namespace Gaufrette;

/**
 * Interface which add supports for metadata
 *
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
interface MetadataSupporter
{
    /**
     * @deprecated
     *
     * @param string $key
     * @param array  $metadata
     */
    public function setMetadata($key, $metadata);

    /**
     * @deprecated
     *
     * @param  string $key
     * @return array
     */
    public function getMetadata($key);

    /**
     * @param string $metaKey
     *
     * @return boolean
     */
    public function isMetadataKeyAllowed($metaKey);
}

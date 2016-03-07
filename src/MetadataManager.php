<?php

namespace carlosV2\DumbsmartRepositories;

use carlosV2\DumbsmartRepositories\Exception\MetadataNotFoundException;

class MetadataManager
{
    /**
     * @var Metadata[]
     */
    private $metadata;

    public function __construct()
    {
        $this->metadata = [];
    }

    /**
     * @param string   $className
     * @param Metadata $metadata
     */
    public function addMetadata($className, Metadata $metadata)
    {
        $this->metadata[$className] = $metadata;
    }

    /**
     * @param object $object
     *
     * @return Metadata
     *
     * @throws MetadataNotFoundException
     */
    public function getMetadataForObject($object)
    {
        $className = get_class($object);
        if (array_key_exists($className, $this->metadata)) {
            return $this->metadata[$className];
        }

        throw new MetadataNotFoundException();
    }
}

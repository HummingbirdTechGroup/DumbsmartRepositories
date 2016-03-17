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
        return $this->getMetadataForClassName(get_class($object));
    }

    /**
     * @param string $className
     *
     * @return Metadata
     *
     * @throws MetadataNotFoundException
     */
    public function getMetadataForClassName($className)
    {
        if (array_key_exists($className, $this->metadata)) {
            return $this->metadata[$className];
        }

        throw new MetadataNotFoundException();
    }
}

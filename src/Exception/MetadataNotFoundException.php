<?php

namespace carlosV2\DumbsmartRepositories\Exception;

class MetadataNotFoundException extends DocumentedException
{
    /**
     * @param string $className
     */
    public function __construct($className)
    {
        parent::__construct(sprintf(
            'Metadata for class `%s` not found.',
            $className
        ));
    }
}

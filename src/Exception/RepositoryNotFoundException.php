<?php

namespace carlosV2\DumbsmartRepositories\Exception;

class RepositoryNotFoundException extends DocumentedException
{
    /**
     * @param string $className
     */
    public function __construct($className)
    {
        parent::__construct(sprintf(
            'Repository for class `%s` not found.',
            $className
        ));
    }
}

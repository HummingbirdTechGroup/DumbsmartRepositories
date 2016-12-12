<?php

namespace carlosV2\DumbsmartRepositories\Exception;

abstract class DocumentedException extends \Exception
{
    const DOCUMENTATION_URL = 'https://github.com/carlosV2/DumbsmartRepositories/blob/master/docs/Exception/';

    /**
     * @param string $message
     * @param string $uri
     */
    public function __construct($message, $uri = self::DOCUMENTATION_URL)
    {
        parent::__construct(sprintf(
            '%s. Please, head to %s%s.md for more details.',
            rtrim($message, '.'),
            $uri,
            $this->getExceptionClassName()
        ));
    }

    /**
     * @return string
     */
    private function getExceptionClassName()
    {
        $reflect = new \ReflectionClass($this);
        return $reflect->getShortName();
    }
}

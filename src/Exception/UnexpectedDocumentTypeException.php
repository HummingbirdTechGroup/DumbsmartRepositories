<?php

namespace carlosV2\DumbsmartRepositories\Exception;

class UnexpectedDocumentTypeException extends DocumentedException
{
    /**
     * @param object $object
     * @param string $field
     * @param string $value
     */
    public function __construct($object, $field, $value)
    {
        parent::__construct(sprintf(
            'Unexpected value of type `%s` found into property `%s` of object `%s`. Only `object` or `null` is allowed.',
            gettype($value),
            $field,
            get_class($object)
        ));
    }
}

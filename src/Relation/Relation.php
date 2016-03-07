<?php

namespace carlosV2\DumbsmartRepositories\Relation;

use carlosV2\DumbsmartRepositories\Transaction;

abstract class Relation
{
    /**
     * @var string
     */
    private $field;

    /**
     * @param string $field
     */
    public function __construct($field)
    {
        $this->field = $field;
    }

    /**
     * @param Transaction $transaction
     * @param object      $object
     */
    abstract public function prepareToSave(Transaction $transaction, $object);

    /**
     * @param Transaction $transaction
     * @param object      $object
     */
    abstract public function prepareToLoad(Transaction $transaction, $object);

    /**
     * @param object $object
     *
     * @return mixed
     */
    protected function extract($object)
    {
        $property = new \ReflectionProperty(get_class($object), $this->field);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    /**
     * @param object $object
     * @param mixed  $document
     */
    protected function inject($object, $document)
    {
        $property = new \ReflectionProperty(get_class($object), $this->field);
        $property->setAccessible(true);
        $property->setValue($object, $document);
    }
}

<?php

namespace carlosV2\DumbsmartRepositories\Relation;

use carlosV2\DumbsmartRepositories\Exception\UnexpectedDocumentTypeException;
use carlosV2\DumbsmartRepositories\Reference;
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
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param Transaction $transaction
     * @param object      $object
     *
     * @throws UnexpectedDocumentTypeException
     */
    abstract public function prepareToSave(Transaction $transaction, $object);

    /**
     * @param Transaction $transaction
     * @param object      $object
     *
     * @throws UnexpectedDocumentTypeException
     */
    abstract public function prepareToLoad(Transaction $transaction, $object);

    /**
     * @param object $object
     *
     * @return mixed
     */
    protected function extract($object)
    {
        return $this->extractByClassName(get_class($object), $object);
    }

    /**
     * @param string $className
     * @param object $object
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    private function extractByClassName($className, $object)
    {
        try {
            $property = new \ReflectionProperty($className, $this->field);
            $property->setAccessible(true);
            return $property->getValue($object);
        } catch (\ReflectionException $e) {
            if (($parentClassName = get_parent_class($className)) !== false) {
                return $this->extractByClassName($parentClassName, $object);
            }

            throw $e;
        }
    }

    /**
     * @param object $object
     * @param mixed  $document
     */
    protected function inject($object, $document)
    {
        $this->injectByClassName(get_class($object), $object, $document);
    }

    /**
     * @param string $className
     * @param object $object
     * @param mixed  $document
     *
     * @throws \ReflectionException
     */
    private function injectByClassName($className, $object, $document)
    {
        try {
            $property = new \ReflectionProperty($className, $this->field);
            $property->setAccessible(true);
            $property->setValue($object, $document);
        } catch (\ReflectionException $e) {
            if (($parentClassName = get_parent_class($className)) !== false) {
                return $this->injectByClassName($parentClassName, $object, $document);
            }

            throw $e;
        }
    }

    /**
     * @param mixed $document
     *
     * @throws UnexpectedDocumentTypeException
     */
    protected function assertObjectOrNull($document)
    {
        if (!is_null($document) && !is_object($document)) {
            throw new UnexpectedDocumentTypeException();
        }
    }

    /**
     * @param mixed $document
     *
     * @throws UnexpectedDocumentTypeException
     */
    protected function assertReferenceOrNull($document)
    {
        if (!is_null($document) && !$document instanceof Reference) {
            throw new UnexpectedDocumentTypeException();
        }
    }
}

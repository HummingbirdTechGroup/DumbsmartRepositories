<?php

namespace carlosV2\DumbsmartRepositories\Relation;

use carlosV2\DumbsmartRepositories\Exception\UnexpectedDocumentTypeException;
use carlosV2\DumbsmartRepositories\Reference;
use carlosV2\DumbsmartRepositories\Transaction;

abstract class Relation implements RelationInterface
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
     * {@inheritdoc}
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareToSave(Transaction $transaction, $object)
    {
        $relation = $this;

        $this->replaceField($object, function ($document) use ($transaction, $object, $relation) {
            $relation->assertObjectOrNull($object, $document);

            return ($document ? $transaction->save($document) : null);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function prepareToLoad(Transaction $transaction, $object)
    {
        $relation = $this;

        $this->replaceField($object, function ($reference) use ($transaction, $object, $relation) {
            $relation->assertReferenceOrNull($object, $reference);

            return ($reference ? $transaction->findByReference($reference) : null);
        });
    }

    /**
     * @param object   $object
     * @param callable $callback
     */
    protected function replaceField($object, $callback)
    {
        From($object)->replace($this->field, $callback);
    }

    /**
     * @internal This method is public to make the code compliant with PHP 5.3. Do NOT rely on this method as
     *           it can be removed at any point or have its visibility changed without previous warning.
     *
     * @param object $object
     * @param mixed  $document
     *
     * @throws UnexpectedDocumentTypeException
     */
    public function assertObjectOrNull($object, $document)
    {
        if (!is_null($document) && !is_object($document)) {
            throw new UnexpectedDocumentTypeException($object, $this->field, $document);
        }
    }

    /**
     * @internal This method is public to make the code compliant with PHP 5.3. Do NOT rely on this method as
     *           it can be removed at any point or have its visibility changed without previous warning.
     *
     * @param object $object
     * @param mixed  $document
     *
     * @throws UnexpectedDocumentTypeException
     */
    public function assertReferenceOrNull($object, $document)
    {
        if (!is_null($document) && !$document instanceof Reference) {
            throw new UnexpectedDocumentTypeException($object, $this->field, $document);
        }
    }
}

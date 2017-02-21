<?php

namespace carlosV2\DumbsmartRepositories;

use carlosV2\DumbsmartRepositories\Relation\RelationInterface;
use Everzet\PersistedObjects\ObjectIdentifier;

class Metadata
{
    /**
     * @var RelationInterface[]
     */
    private $relations;

    /**
     * @var ObjectIdentifier
     */
    private $identifier;

    /**
     * @param ObjectIdentifier $identifier
     */
    public function __construct(ObjectIdentifier $identifier)
    {
        $this->relations = [];
        $this->identifier = $identifier;
    }

    /**
     * @param RelationInterface $relation
     */
    public function setRelation(RelationInterface $relation)
    {
        $this->relations[] = $relation;
    }

    /**
     * @param Transaction $transaction
     * @param object      $object
     */
    public function prepareToSave(Transaction $transaction, $object)
    {
        foreach ($this->relations as $relation) {
            $relation->prepareToSave($transaction, $object);
        }
    }

    /**
     * @param Transaction $transaction
     * @param object      $object
     */
    public function prepareToLoad(Transaction $transaction, $object)
    {
        foreach ($this->relations as $relation) {
            $relation->prepareToLoad($transaction, $object);
        }
    }

    /**
     * @param object $object
     *
     * @return Reference
     */
    public function getReferenceForObject($object)
    {
        return new Reference(get_class($object), $this->identifier->getIdentity($object));
    }

    /**
     * @return ObjectIdentifier
     */
    public function getObjectIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return RelationInterface[]
     */
    public function getRelations()
    {
        return $this->relations;
    }
}

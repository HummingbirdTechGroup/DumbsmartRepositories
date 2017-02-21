<?php

namespace carlosV2\DumbsmartRepositories\Relation;

class OneToManyRelation extends Relation implements RelationInterface
{
    /**
     * {@inheritdoc}
     */
    protected function replaceField($object, callable $callback)
    {
        parent::replaceField($object, function ($documents) use ($callback) {
            return array_map($callback, $documents);
        });
    }
}

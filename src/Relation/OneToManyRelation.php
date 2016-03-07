<?php

namespace carlosV2\DumbsmartRepositories\Relation;

use carlosV2\DumbsmartRepositories\Transaction;

class OneToManyRelation extends Relation
{
    /**
     * {@inheritdoc}
     */
    public function prepareToSave(Transaction $transaction, $object)
    {
        $this->inject($object, array_map(function ($document) use ($transaction) {
            return $transaction->save($document);
        }, $this->extract($object)));
    }

    /**
     * {@inheritdoc}
     */
    public function prepareToLoad(Transaction $transaction, $object)
    {
        $this->inject($object, array_map(function ($document) use ($transaction) {
            return $transaction->findByReference($document);
        }, $this->extract($object)));
    }
}

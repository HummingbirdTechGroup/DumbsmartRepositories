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
            $this->assertObjectOrNull($document);

            return ($document ? $transaction->save($document) : null);
        }, $this->extract($object)));
    }

    /**
     * {@inheritdoc}
     */
    public function prepareToLoad(Transaction $transaction, $object)
    {
        $this->inject($object, array_map(function ($reference) use ($transaction) {
            $this->assertReferenceOrNull($reference);

            return ($reference ? $transaction->findByReference($reference) : null);
        }, $this->extract($object)));
    }
}

<?php

namespace carlosV2\DumbsmartRepositories\Relation;

use carlosV2\DumbsmartRepositories\Transaction;

class OneToOneRelation extends Relation
{
    /**
     * {@inheritdoc}
     */
    public function prepareToSave(Transaction $transaction, $object)
    {
        $this->inject($object, $transaction->save($this->extract($object)));
    }

    /**
     * {@inheritdoc}
     */
    public function prepareToLoad(Transaction $transaction, $object)
    {
        $this->inject($object, $transaction->findByReference($this->extract($object)));
    }
}

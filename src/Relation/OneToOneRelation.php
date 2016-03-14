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
        $document = $this->extract($object);
        $this->assertObjectOrNull($document);

        $this->inject($object, ($document ? $transaction->save($document) : null));
    }

    /**
     * {@inheritdoc}
     */
    public function prepareToLoad(Transaction $transaction, $object)
    {
        $reference = $this->extract($object);
        $this->assertReferenceOrNull($reference);

        $this->inject($object, ($reference ? $transaction->findByReference($reference) : null));
    }
}

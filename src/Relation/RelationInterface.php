<?php

namespace carlosV2\DumbsmartRepositories\Relation;

use carlosV2\DumbsmartRepositories\Exception\UnexpectedDocumentTypeException;
use carlosV2\DumbsmartRepositories\Transaction;

interface RelationInterface
{
    /**
     * @return string
     */
    public function getField();

    /**
     * @param Transaction $transaction
     * @param object      $object
     *
     * @throws UnexpectedDocumentTypeException
     */
    public function prepareToSave(Transaction $transaction, $object);

    /**
     * @param Transaction $transaction
     * @param object      $object
     *
     * @throws UnexpectedDocumentTypeException
     */
    public function prepareToLoad(Transaction $transaction, $object);
}

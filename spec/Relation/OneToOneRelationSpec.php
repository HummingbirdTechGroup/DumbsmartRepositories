<?php

namespace spec\carlosV2\DumbsmartRepositories\Relation;

use carlosV2\DumbsmartRepositories\Reference;
use carlosV2\DumbsmartRepositories\Relation\Relation;
use carlosV2\DumbsmartRepositories\Transaction;
use PhpSpec\ObjectBehavior;

class OneToOneRelationSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('field');
    }

    function it_is_a_Relation()
    {
        $this->shouldHaveType(Relation::class);
    }

    function it_prepares_an_object_to_be_saved(Transaction $transaction)
    {
        $embedded = new \stdClass();
        $reference = new Reference('classname', '123');

        $object = new TestingObject();
        $object->setField($embedded);

        $transaction->save($embedded)->willReturn($reference);

        $this->prepareToSave($transaction, $object);
        expect($object->getField())->toBe($reference);
    }

    function it_prepares_an_object_to_be_loaded(Transaction $transaction)
    {
        $embedded = new \stdClass();
        $reference = new Reference('classname', '123');

        $object = new TestingObject();
        $object->setField($reference);

        $transaction->findByReference($reference)->willReturn($embedded);

        $this->prepareToLoad($transaction, $object);
        expect($object->getField())->toBe($embedded);
    }
}

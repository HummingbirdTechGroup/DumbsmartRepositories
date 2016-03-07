<?php

namespace spec\carlosV2\DumbsmartRepositories\Relation;

use carlosV2\DumbsmartRepositories\Reference;
use carlosV2\DumbsmartRepositories\Relation\Relation;
use carlosV2\DumbsmartRepositories\Transaction;
use PhpSpec\ObjectBehavior;

class OneToManyRelationSpec extends ObjectBehavior
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
        $embedded1 = new \stdClass();
        $embedded1->id = '123';
        $embedded2 = new \stdClass();
        $embedded2->id = '456';
        $reference1 = new Reference('classname', '123');
        $reference2 = new Reference('classname', '456');

        $object = new TestingObject();
        $object->setField([$embedded1, $embedded2]);

        $transaction->save($embedded1)->willReturn($reference1);
        $transaction->save($embedded2)->willReturn($reference2);

        $this->prepareToSave($transaction, $object);
        expect($object->getField())->toBe([$reference1, $reference2]);
    }

    function it_prepares_an_object_to_be_loaded(Transaction $transaction)
    {
        $embedded1 = new \stdClass();
        $embedded1->id = '123';
        $embedded2 = new \stdClass();
        $embedded2->id = '456';
        $reference1 = new Reference('classname', '123');
        $reference2 = new Reference('classname', '456');

        $object = new TestingObject();
        $object->setField([$reference1, $reference2]);

        $transaction->findByReference($reference1)->willReturn($embedded1);
        $transaction->findByReference($reference2)->willReturn($embedded2);

        $this->prepareToLoad($transaction, $object);
        expect($object->getField())->toBe([$embedded1, $embedded2]);
    }
}

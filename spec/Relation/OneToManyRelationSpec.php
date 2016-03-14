<?php

namespace spec\carlosV2\DumbsmartRepositories\Relation;

use carlosV2\DumbsmartRepositories\Exception\UnexpectedDocumentTypeException;
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

    function it_prepares_an_object_to_be_saved_with_related_objects_and_nulls(Transaction $transaction)
    {
        $embedded1 = new \stdClass();
        $embedded1->id = '123';
        $embedded2 = new \stdClass();
        $embedded2->id = '456';
        $reference1 = new Reference('classname', '123');
        $reference2 = new Reference('classname', '456');

        $object = new TestingObject();
        $object->setField([$embedded1, null, $embedded2]);

        $transaction->save($embedded1)->willReturn($reference1);
        $transaction->save($embedded2)->willReturn($reference2);
        $transaction->save(null)->shouldNotBeCalled();

        $this->prepareToSave($transaction, $object);
        expect($object->getField())->toBe([$reference1, null, $reference2]);
    }

    function it_throws_UnexpectedDocumentTypeException_if_a_related_value_is_not_null_or_object_while_preparing_to_save(Transaction $transaction)
    {
        $embedded1 = new \stdClass();
        $embedded1->id = '123';
        $embedded2 = new \stdClass();
        $embedded2->id = '456';
        $reference1 = new Reference('classname', '123');
        $reference2 = new Reference('classname', '456');

        $object = new TestingObject();
        $object->setField([$embedded1, null, $embedded2, 2]);

        $transaction->save($embedded1)->willReturn($reference1);
        $transaction->save($embedded2)->willReturn($reference2);
        $transaction->save(null)->shouldNotBeCalled();
        $transaction->save(2)->shouldNotBeCalled();

        $this->shouldThrow(UnexpectedDocumentTypeException::class)->duringPrepareToSave($transaction, $object);
    }

    function it_prepares_an_object_to_be_loaded_with_related_references_and_nulls(Transaction $transaction)
    {
        $embedded1 = new \stdClass();
        $embedded1->id = '123';
        $embedded2 = new \stdClass();
        $embedded2->id = '456';
        $reference1 = new Reference('classname', '123');
        $reference2 = new Reference('classname', '456');

        $object = new TestingObject();
        $object->setField([$reference1, null, $reference2]);

        $transaction->findByReference($reference1)->willReturn($embedded1);
        $transaction->findByReference($reference2)->willReturn($embedded2);
        $transaction->findByReference(null)->shouldNotBeCalled();

        $this->prepareToLoad($transaction, $object);
        expect($object->getField())->toBe([$embedded1, null, $embedded2]);
    }

    function it_throws_UnexpectedDocumentTypeException_if_a_related_value_is_not_null_or_reference_while_preparing_to_load(Transaction $transaction)
    {
        $embedded1 = new \stdClass();
        $embedded1->id = '123';
        $embedded2 = new \stdClass();
        $embedded2->id = '456';
        $embedded3 = new \stdClass();
        $embedded3->id = '789';
        $reference1 = new Reference('classname', '123');
        $reference2 = new Reference('classname', '456');

        $object1 = new TestingObject();
        $object1->setField([$reference1, null, $reference2, 2]);
        $object2 = new TestingObject();
        $object2->setField([$reference1, null, $reference2, $embedded3]);

        $transaction->findByReference($reference1)->willReturn($embedded1);
        $transaction->findByReference($reference2)->willReturn($embedded2);
        $transaction->findByReference(null)->shouldNotBeCalled();
        $transaction->findByReference(2)->shouldNotBeCalled();
        $transaction->findByReference($embedded3)->shouldNotBeCalled();

        $this->shouldThrow(UnexpectedDocumentTypeException::class)->duringPrepareToLoad($transaction, $object1);
        $this->shouldThrow(UnexpectedDocumentTypeException::class)->duringPrepareToLoad($transaction, $object2);
    }
}

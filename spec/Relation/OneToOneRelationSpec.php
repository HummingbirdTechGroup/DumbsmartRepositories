<?php

namespace spec\carlosV2\DumbsmartRepositories\Relation;

use carlosV2\DumbsmartRepositories\Exception\UnexpectedDocumentTypeException;
use carlosV2\DumbsmartRepositories\Reference;
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
        $this->shouldHaveType('carlosV2\DumbsmartRepositories\Relation\Relation');
    }

    function it_exposes_the_field()
    {
        $this->getField()->shouldReturn('field');
    }

    function it_prepares_an_object_to_be_saved_with_a_related_object(Transaction $transaction)
    {
        $embedded = new \stdClass();
        $reference = new Reference('classname', '123');

        $object = new TestingObject();
        $object->setField($embedded);

        $transaction->save($embedded)->willReturn($reference);

        $this->prepareToSave($transaction, $object);
        expect($object->getField())->toBe($reference);
    }

    function it_prepares_an_object_to_be_saved_with_a_related_object_from_a_parent_class(Transaction $transaction)
    {
        $embedded = new \stdClass();
        $reference = new Reference('classname', '123');

        $object = new TestingChildObject();
        $object->setField($embedded);

        $transaction->save($embedded)->willReturn($reference);

        $this->prepareToSave($transaction, $object);
        expect($object->getField())->toBe($reference);
    }

    function it_prepares_an_object_to_be_saved_with_a_related_null(Transaction $transaction)
    {
        $object = new TestingObject();
        $object->setField(null);

        $transaction->save(null)->shouldNotBeCalled();

        $this->prepareToSave($transaction, $object);
        expect($object->getField())->toBe(null);
    }

    function it_prepares_an_object_to_be_saved_with_a_related_null_from_a_parent_class(Transaction $transaction)
    {
        $object = new TestingChildObject();
        $object->setField(null);

        $transaction->save(null)->shouldNotBeCalled();

        $this->prepareToSave($transaction, $object);
        expect($object->getField())->toBe(null);
    }

    function it_throws_UnexpectedDocumentTypeException_if_the_related_value_is_not_null_or_object_while_preparing_to_save(Transaction $transaction)
    {
        $object = new TestingObject();
        $object->setField(2);

        $transaction->save(2)->shouldNotBeCalled();

        $this->shouldThrow(new UnexpectedDocumentTypeException(
            $object,
            'field',
            2
        ))->duringPrepareToSave($transaction, $object);
    }

    function it_prepares_an_object_to_be_loaded_with_a_related_reference(Transaction $transaction)
    {
        $embedded = new \stdClass();
        $reference = new Reference('classname', '123');

        $object = new TestingObject();
        $object->setField($reference);

        $transaction->findByReference($reference)->willReturn($embedded);

        $this->prepareToLoad($transaction, $object);
        expect($object->getField())->toBe($embedded);
    }

    function it_prepares_an_object_to_be_loaded_with_a_related_reference_from_a_parent_class(Transaction $transaction)
    {
        $embedded = new \stdClass();
        $reference = new Reference('classname', '123');

        $object = new TestingChildObject();
        $object->setField($reference);

        $transaction->findByReference($reference)->willReturn($embedded);

        $this->prepareToLoad($transaction, $object);
        expect($object->getField())->toBe($embedded);
    }

    function it_prepares_an_object_to_be_loaded_with_a_related_null(Transaction $transaction)
    {
        $object = new TestingObject();
        $object->setField(null);

        $transaction->findByReference(null)->shouldNotBeCalled();

        $this->prepareToLoad($transaction, $object);
        expect($object->getField())->toBe(null);
    }

    function it_prepares_an_object_to_be_loaded_with_a_related_null_from_a_parent_class(Transaction $transaction)
    {
        $object = new TestingChildObject();
        $object->setField(null);

        $transaction->findByReference(null)->shouldNotBeCalled();

        $this->prepareToLoad($transaction, $object);
        expect($object->getField())->toBe(null);
    }

    function it_throws_UnexpectedDocumentTypeException_if_the_related_value_is_not_null_or_reference_while_preparing_to_load(Transaction $transaction)
    {
        $embedded = new \stdClass();

        $object1 = new TestingObject();
        $object1->setField(2);
        $object2 = new TestingObject();
        $object2->setField($embedded);

        $transaction->findByReference(2)->shouldNotBeCalled();
        $transaction->findByReference($embedded)->shouldNotBeCalled();

        $this->shouldThrow(new UnexpectedDocumentTypeException(
            $object1,
            'field',
            2
        ))->duringPrepareToLoad($transaction, $object1);

        $this->shouldThrow(new UnexpectedDocumentTypeException(
            $object2,
            'field',
            $embedded
        ))->duringPrepareToLoad($transaction, $object2);
    }
}

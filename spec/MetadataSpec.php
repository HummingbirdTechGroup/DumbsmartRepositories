<?php

namespace spec\carlosV2\DumbsmartRepositories;

use carlosV2\DumbsmartRepositories\Relation\Relation;
use carlosV2\DumbsmartRepositories\Transaction;
use Everzet\PersistedObjects\ObjectIdentifier;
use PhpSpec\ObjectBehavior;

class MetadataSpec extends ObjectBehavior
{
    function let(ObjectIdentifier $identifier)
    {
        $this->beConstructedWith($identifier);
    }

    function it_exposes_the_ObjectIdentifier(ObjectIdentifier $identifier)
    {
        $this->getObjectIdentifier()->shouldReturn($identifier);
    }

    function it_computes_the_reference_to_an_object(ObjectIdentifier $identifier)
    {
        $object = new \stdClass();

        $identifier->getIdentity($object)->willReturn('id');

        $reference = $this->getReferenceForObject($object);
        $reference->shouldBeAnInstanceOf('carlosV2\DumbsmartRepositories\Reference');
        $reference->getClassName()->shouldReturn('stdClass');
        $reference->getId()->shouldReturn('id');
    }

    function it_prepares_the_object_to_be_saved(Relation $relation1, Relation $relation2, Transaction $transaction)
    {
        $object = new \stdClass();

        $relation1->prepareToSave($transaction, $object)->shouldBeCalled();
        $relation2->prepareToSave($transaction, $object)->shouldBeCalled();

        $this->setRelation($relation1);
        $this->setRelation($relation2);
        $this->prepareToSave($transaction, $object);
    }

    function it_prepares_the_object_to_be_loaded(Relation $relation1, Relation $relation2, Transaction $transaction)
    {
        $object = new \stdClass();

        $relation1->prepareToLoad($transaction, $object)->shouldBeCalled();
        $relation2->prepareToLoad($transaction, $object)->shouldBeCalled();

        $this->setRelation($relation1);
        $this->setRelation($relation2);
        $this->prepareToLoad($transaction, $object);
    }

    function it_exposes_the_relations(Relation $relation1, Relation $relation2)
    {
        $this->setRelation($relation1);
        $this->setRelation($relation2);
        $this->getRelations()->shouldReturn([$relation1, $relation2]);
    }
}

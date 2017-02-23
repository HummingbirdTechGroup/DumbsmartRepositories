<?php

namespace spec\carlosV2\DumbsmartRepositories;

use carlosV2\DumbsmartRepositories\Metadata;
use carlosV2\DumbsmartRepositories\MetadataManager;
use carlosV2\DumbsmartRepositories\Reference;
use carlosV2\DumbsmartRepositories\RepositoryManager;
use Everzet\PersistedObjects\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransactionSpec extends ObjectBehavior
{
    function let(MetadataManager $mm, Metadata $metadata, RepositoryManager $rm, Repository $repository)
    {
        $mm->getMetadataForObject(Argument::type('\stdClass'))->willReturn($metadata);
        $rm->getRepositoryForObject(Argument::type('\stdClass'))->willReturn($repository);
        $rm->getRepositoryForClassName('my_class')->willReturn($repository);

        $this->beConstructedWith($mm, $rm);
    }

    function it_persists_a_new_object(Metadata $metadata, Repository $repository)
    {
        $object = new \stdClass();
        $reference = new Reference('my_class', '123');

        $metadata->getReferenceForObject($object)->willReturn($reference);
        $metadata->prepareToSave($this, $object)->shouldBeCalled();
        $repository->save($object)->shouldBeCalled();

        $this->save($object)->shouldReturn($reference);
    }

    function it_returns_same_reference_when_trying_to_persists_the_same_object(Metadata $metadata, Repository $repository)
    {
        $object = new \stdClass();
        $reference = new Reference('my_class', '123');

        $metadata->getReferenceForObject($object)->willReturn($reference);
        $metadata->prepareToSave($this, $object)->shouldBeCalled();
        $repository->save($object)->shouldBeCalled();

        $this->save($object)->shouldReturn($reference);
        $this->save($object)->shouldReturn($reference);
    }

    function it_finds_an_object_by_reference(Metadata $metadata, Repository $repository)
    {
        $object = new \stdClass();
        $reference = new Reference('my_class', '123');

        $repository->findById('123')->willReturn($object);
        $metadata->getReferenceForObject($object)->willReturn($reference);
        $metadata->prepareToLoad($this, $object)->shouldBeCalled();

        $this->findByReference($reference)->shouldBeAnInstanceOf('\stdClass');
    }

    function it_returns_null_if_an_object_cannot_be_found(Metadata $metadata, Repository $repository)
    {
        $object = new \stdClass();
        $reference = new Reference('my_class', '123');

        $repository->findById('123')->willReturn(null);
        $metadata->getReferenceForObject($object)->willReturn($reference);
        $metadata->prepareToLoad($this, null)->shouldNotBeCalled();

        $this->findByReference($reference)->shouldReturn(null);
    }

    function it_returns_same_object_when_trying_to_find_by_the_same_reference(Metadata $metadata, Repository $repository)
    {
        $object = new \stdClass();
        $reference = new Reference('my_class', '123');

        $repository->findById('123')->willReturn($object);
        $metadata->getReferenceForObject($object)->willReturn($reference);
        $metadata->prepareToLoad($this, $object)->shouldBeCalled();

        $result1 = $this->findByReference($reference);
        $result2 = $this->findByReference($reference);

        expect($result1->getWrappedObject())->toBe($result2->getWrappedObject());
    }

    function it_gets_all_the_objects_in_the_repository(Metadata $metadata, Repository $repository)
    {
        $object1 = new \stdClass();
        $object1->id = '123';
        $reference1 = new Reference('my_class', '123');
        $object2 = new \stdClass();
        $object2->id = '456';
        $reference2 = new Reference('my_class', '456');

        $repository->getAll()->willReturn(array($object1, $object2));
        $repository->findById('123')->willReturn($object1);
        $repository->findById('456')->willReturn($object2);
        $metadata->getReferenceForObject($object1)->willReturn($reference1);
        $metadata->getReferenceForObject($object2)->willReturn($reference2);
        $metadata->prepareToLoad($this, $object1)->shouldBeCalled();
        $metadata->prepareToLoad($this, $object2)->shouldBeCalled();

        $results = $this->getAll('my_class');
        $results->shouldHaveCount(2);
        $results[0]->shouldBeAnInstanceOf('\stdClass');
        $results[0]->id->shouldBe('123');
        $results[1]->shouldBeAnInstanceOf('\stdClass');
        $results[1]->id->shouldBe('456');
    }
}

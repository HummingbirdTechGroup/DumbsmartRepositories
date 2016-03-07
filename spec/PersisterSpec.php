<?php

namespace spec\carlosV2\DumbsmartRepositories;

use carlosV2\DumbsmartRepositories\Exception\RepositoryNotFoundException;
use carlosV2\DumbsmartRepositories\Reference;
use carlosV2\DumbsmartRepositories\RepositoryManager;
use carlosV2\DumbsmartRepositories\Transaction;
use carlosV2\DumbsmartRepositories\TransactionFactory;
use Everzet\PersistedObjects\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PersisterSpec extends ObjectBehavior
{
    function let(RepositoryManager $rm, Repository $repository, TransactionFactory $factory, Transaction $transaction)
    {
        $factory->createTransaction()->willReturn($transaction);
        $rm->getRepositoryForObject(Argument::type(\stdClass::class))->willReturn($repository);
        $rm->getRepositoryForClassName('my_class')->willReturn($repository);

        $this->beConstructedWith($rm, $factory);
    }

    function it_persists_an_object(Transaction $transaction)
    {
        $object = new \stdClass();

        $transaction->save($object)->shouldBeCalled();

        $this->save($object);
    }

    function it_finds_an_object_by_its_id(Transaction $transaction)
    {
        $object = new \stdClass();

        $transaction->findByReference(Argument::allOf(
            Argument::type(Reference::class),
            Argument::which('getClassName', 'my_class'),
            Argument::which('getId', '123')
        ))->willReturn($object);

        $this->findById('my_class', '123')->shouldReturn($object);
    }

    function it_gets_all_the_objects(Transaction $transaction)
    {
        $object1 = new \stdClass();
        $object2 = new \stdClass();

        $transaction->getAll('my_class')->willReturn([$object1, $object2]);

        $this->getAll('my_class')->shouldReturn([$object1, $object2]);
    }

    function it_removes_an_object(Repository $repository)
    {
        $object = new \stdClass();

        $repository->remove($object)->shouldBeCalled();

        $this->remove($object);
    }

    function it_bubbles_up_the_exception_if_there_are_no_associated_repositories_while_removing(RepositoryManager $rm)
    {
        $object = new \stdClass();

        $rm->getRepositoryForObject($object)->willThrow(RepositoryNotFoundException::class);

        $this->shouldThrow(RepositoryNotFoundException::class)->duringRemove($object);
    }

    function it_clears_all_the_objects(Repository $repository)
    {
        $repository->clear()->shouldBeCalled();

        $this->clear('my_class');
    }

    function it_bubbles_up_the_exception_if_there_are_no_associated_repositories_while_clearing(RepositoryManager $rm)
    {
        $rm->getRepositoryForClassName('my_class')->willThrow(RepositoryNotFoundException::class);

        $this->shouldThrow(RepositoryNotFoundException::class)->duringClear('my_class');
    }
}

<?php

namespace spec\carlosV2\DumbsmartRepositories;

use Everzet\PersistedObjects\Repository;
use PhpSpec\ObjectBehavior;

class RepositoryManagerSpec extends ObjectBehavior
{
    function it_provides_the_repository_assigned_to_an_object(Repository $repository)
    {
        $this->addRepository('stdClass', $repository);
        $this->getRepositoryForObject(new \stdClass())->shouldReturn($repository);
    }

    function it_throws_an_exception_if_the_object_has_no_repository_assigned()
    {
        $this->shouldThrow('carlosV2\DumbsmartRepositories\Exception\RepositoryNotFoundException')->duringGetRepositoryForObject(new \stdClass());
    }

    function it_provides_the_repository_assigned_to_a_classname(Repository $repository)
    {
        $this->addRepository('stdClass', $repository);
        $this->getRepositoryForClassName('stdClass')->shouldReturn($repository);
    }

    function it_throws_an_exception_if_the_classname_has_no_repository_assigned()
    {
        $this->shouldThrow('carlosV2\DumbsmartRepositories\Exception\RepositoryNotFoundException')->duringGetRepositoryForClassName('stdClass');
    }
}

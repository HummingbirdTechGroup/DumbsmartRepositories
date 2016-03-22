<?php

namespace spec\carlosV2\DumbsmartRepositories;

use carlosV2\DumbsmartRepositories\Metadata;
use PhpSpec\ObjectBehavior;

class MetadataManagerSpec extends ObjectBehavior
{
    function it_provides_the_metadata_assigned_to_an_object(Metadata $metadata)
    {
        $this->addMetadata('stdClass', $metadata);
        $this->getMetadataForObject(new \stdClass())->shouldReturn($metadata);
    }

    function it_throws_an_exception_if_the_object_has_no_metadata_assigned()
    {
        $this->shouldThrow('carlosV2\DumbsmartRepositories\Exception\MetadataNotFoundException')->duringGetMetadataForObject(new \stdClass());
    }

    function it_provides_the_repository_assigned_to_a_classname(Metadata $metadata)
    {
        $this->addMetadata('stdClass', $metadata);
        $this->getMetadataForClassName('stdClass')->shouldReturn($metadata);
    }

    function it_throws_an_exception_if_the_classname_has_no_repository_assigned()
    {
        $this->shouldThrow('carlosV2\DumbsmartRepositories\Exception\MetadataNotFoundException')->duringGetMetadataForClassName('stdClass');
    }
}

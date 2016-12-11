<?php

namespace spec\carlosV2\DumbsmartRepositories\Exception;

use PhpSpec\ObjectBehavior;

class UnexpectedDocumentTypeExceptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new \stdClass(), 'field', 4);
    }

    function it_is_an_Exception()
    {
        $this->shouldHaveType('\Exception');
    }

    function it_is_a_DocumentedException()
    {
        $this->shouldHaveType('carlosV2\DumbsmartRepositories\Exception\DocumentedException');
    }
}

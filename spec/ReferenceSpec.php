<?php

namespace spec\carlosV2\DumbsmartRepositories;

use PhpSpec\ObjectBehavior;

class ReferenceSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('classname', 'id');
    }

    function it_exposes_the_classname()
    {
        $this->getClassName()->shouldReturn('classname');
    }

    function it_exposes_the_id()
    {
        $this->getId()->shouldReturn('id');
    }
}

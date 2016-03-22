<?php

namespace spec\carlosV2\DumbsmartRepositories;

use carlosV2\DumbsmartRepositories\MetadataManager;
use carlosV2\DumbsmartRepositories\RepositoryManager;
use PhpSpec\ObjectBehavior;

class TransactionFactorySpec extends ObjectBehavior
{
    function it_creates_transactions(MetadataManager $mm, RepositoryManager $rm)
    {
        $this->beConstructedWith($mm, $rm);
        $this->createTransaction()->shouldBeAnInstanceOf('carlosV2\DumbsmartRepositories\Transaction');
    }
}

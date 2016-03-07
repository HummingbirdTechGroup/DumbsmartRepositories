<?php

namespace carlosV2\DumbsmartRepositories;

class TransactionFactory
{
    /**
     * @var MetadataManager
     */
    private $mm;

    /**
     * @var RepositoryManager
     */
    private $rm;

    /**
     * @param MetadataManager   $mm
     * @param RepositoryManager $rm
     */
    public function __construct(MetadataManager $mm, RepositoryManager $rm)
    {
        $this->mm = $mm;
        $this->rm = $rm;
    }

    /**
     * @return Transaction
     */
    public function createTransaction()
    {
        return new Transaction($this->mm, $this->rm);
    }
}

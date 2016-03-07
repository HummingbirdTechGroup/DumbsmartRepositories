<?php

namespace carlosV2\DumbsmartRepositories;

use carlosV2\DumbsmartRepositories\Exception\RepositoryNotFoundException;

class Persister
{
    /**
     * @var RepositoryManager
     */
    private $rm;

    /**
     * @var TransactionFactory
     */
    private $factory;

    /**
     * @param RepositoryManager  $rm
     * @param TransactionFactory $factory
     */
    public function __construct(RepositoryManager $rm, TransactionFactory $factory)
    {
        $this->rm = $rm;
        $this->factory = $factory;
    }

    /**
     * @param object $object
     */
    public function save($object)
    {
        $this->factory->createTransaction()->save($object);
    }

    /**
     * @param string $className
     * @param string $id
     *
     * @return object
     */
    public function findById($className, $id)
    {
        return $this->factory->createTransaction()->findByReference(new Reference($className, $id));
    }

    /**
     * @param string $className
     *
     * @return object[]
     */
    public function getAll($className)
    {
        return $this->factory->createTransaction()->getAll($className);
    }

    /**
     * @param object $object
     *
     * @throws RepositoryNotFoundException
     */
    public function remove($object)
    {
        $this->rm->getRepositoryForObject($object)->remove($object);
    }

    /**
     * @param string $className
     *
     * @throws RepositoryNotFoundException
     */
    public function clear($className)
    {
        $this->rm->getRepositoryForClassName($className)->clear();
    }
}

<?php

namespace carlosV2\DumbsmartRepositories;

use carlosV2\DumbsmartRepositories\Exception\RepositoryNotFoundException;
use Everzet\PersistedObjects\Repository;

class RepositoryManager
{
    /**
     * @var Repository[]
     */
    private $repositories;

    public function __construct()
    {
        $this->repositories = [];
    }

    /**
     * @param string     $className
     * @param Repository $repository
     */
    public function addRepository($className, Repository $repository)
    {
        $this->repositories[$className] = $repository;
    }

    /**
     * @param object $object
     *
     * @return Repository
     *
     * @throws RepositoryNotFoundException
     */
    public function getRepositoryForObject($object)
    {
        return $this->getRepositoryForClassName(get_class($object));
    }

    /**
     * @param string $className
     *
     * @return Repository
     *
     * @throws RepositoryNotFoundException
     */
    public function getRepositoryForClassName($className)
    {
        if (array_key_exists($className, $this->repositories)) {
            return $this->repositories[$className];
        }

        throw new RepositoryNotFoundException($className);
    }
}

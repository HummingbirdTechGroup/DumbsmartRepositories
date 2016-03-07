<?php

namespace carlosV2\DumbsmartRepositories;

class Reference
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $id;

    /**
     * @param string $className
     * @param string $id
     */
    public function __construct($className, $id)
    {
        $this->className = $className;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}

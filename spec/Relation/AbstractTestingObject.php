<?php

namespace spec\carlosV2\DumbsmartRepositories\Relation;

abstract class AbstractTestingObject
{
    private $field;

    public function setField($data)
    {
        $this->field = $data;
    }

    public function getField()
    {
        return $this->field;
    }
}

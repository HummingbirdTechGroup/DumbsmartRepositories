<?php

namespace spec\carlosV2\DumbsmartRepositories\Relation;

class TestingObject
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

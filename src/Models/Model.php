<?php

namespace App\Models;

abstract class Model
{
    public function __construct($dataObj = null, $params = null){
        if (!empty($dataObj))
            $this->fromMap($dataObj,$params);
    }

    public function fromMap($dataObj, array $params = null)
    {
        foreach ($dataObj as $key => $item)
        {
            $this->{$key} = $item;
        }

        foreach ($params as $key => $item)
        {
            $this->{$key} = $item;
        }
    }
}
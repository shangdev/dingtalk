<?php

namespace EasyDingTalk;

use Pimple\Container;

class Application extends Container
{
    /**
     * @var array
     */
    protected $providers = [];

    public function __constract()
    {
        parent::__constract();
    }
}
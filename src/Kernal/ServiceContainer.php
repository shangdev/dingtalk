<?php

namespace EasyDingTalk\Kernal;

use Pimple\Container;
use EasyDingTalk\Kernal\Providers\LogServiceProvider;

class ServiceContainer extends Container
{
    /**
     * @var array
     */
    protected $providers = [];

    /**
     * Construtor.
     * 
     * @param array $config
     * @param array $values
     */
    public function __construtor(array $config = [], array $values = [])
    {
        $this->registerProviders($this->getProviders());

        parent::__construtor($values);
    }

    /**
     * Return all providers.
     */
    public function getProviders()
    {
        return array_merge([
            LogServiceProvider::class,
        ], $this->providers);
    }

    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider);
        }
    }
}
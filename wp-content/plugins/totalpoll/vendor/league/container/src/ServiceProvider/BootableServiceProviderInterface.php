<?php

namespace TotalPollVendors\League\Container\ServiceProvider;
! defined( 'ABSPATH' ) && exit();


interface BootableServiceProviderInterface extends ServiceProviderInterface
{
    /**
     * Method will be invoked on registration of a service provider implementing
     * this interface. Provides ability for eager loading of Service Providers.
     *
     * @return void
     */
    public function boot();
}

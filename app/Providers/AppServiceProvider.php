<?php

namespace App\Providers;

use App\Observers\CustomerObserver;
use Illuminate\Support\ServiceProvider;

use App\Campaign;
use App\Customer;

use App\Observers\CampaignObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Campaign::observe(CampaignObserver::class);
        Customer::observe(CustomerObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

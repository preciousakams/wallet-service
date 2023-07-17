<?php

namespace App\Providers;

use App\Repository\IzyBudget\IzyBudgetInterface;
use App\Repository\IzyBudget\IzyBudgetService;
use App\Repository\IzyPay\IzyPayInterface;
use App\Repository\IzyPay\IzyPayService;
use App\Repository\Referral\ReferralInterface;
use App\Repository\Referral\ReferralService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind(IzyPayInterface::class, IzyPayService::class);
        $this->app->bind(IzyBudgetInterface::class, IzyBudgetService::class);
        $this->app->bind(ReferralInterface::class, ReferralService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

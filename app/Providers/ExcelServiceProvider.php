<?php

namespace App\Providers;

use App\Services\ExcelParserService;
use Illuminate\Support\ServiceProvider;

class ExcelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ExcelParserService::class, function () {
            return new ExcelParserService();
        });
    }

    public function boot()
    {
        //
    }
}
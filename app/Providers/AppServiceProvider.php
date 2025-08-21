<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Modo 1 - simples e direto - para classes Concreta.
        //app()->bind(\App\Contracts\ApiInterface::class, \App\Library\ApiExample::class);
        
        
        /*//Modo 2 - Aplicando complexidade para instanciar.
        app()->bind(\App\Contracts\ApiInterface::class, function () {
            //... código com complexidades para gerar novo objeto
            return new \App\Library\ApiExample();
        });*/

        //Modo 2.1 - Aprimorando a Closure (callback).
        app()->bind(\App\Contracts\ApiInterface::class, fn() => new \App\Library\ApiExample());
    }

    
    public function boot(): void
    {
        //
    }
}

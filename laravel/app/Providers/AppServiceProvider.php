<?php

namespace App\Providers;

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
        $this->app->singleton(\App\Support\Graphql\InputDefinitionExtractorInterface::class, \App\Support\Graphql\InputDefinitionExtractor::class);
        $this->app->singleton(\App\Support\Validation\ValidationExtractorInterface::class, \App\Support\Validation\UseCaseInputValidationExtractor::class);
        $this->app->singleton(\App\Exports\ExportFileManagerInterface::class, \App\Exports\FileManager::class);
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

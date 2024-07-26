<?php

namespace Astroselling\AstroExceptions;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AstroExceptionsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('astro-exceptions')
            ->hasConfigFile();
    }
}

<?php

namespace Tiknil\BsBladeForms\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Tiknil\BsBladeForms\BsBladeFormsServiceProvider;

class TestCase extends OrchestraTestCase
{
    use InteractsWithViews;

    protected function setUp(): void
    {
        parent::setUp();

        bindModel(null);

    }

    protected function getPackageProviders($app)
    {
        return [
            BsBladeFormsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}

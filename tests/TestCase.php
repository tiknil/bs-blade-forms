<?php

namespace Tiknil\BsBladeForms\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Tiknil\BsBladeForms\BsBladeFormsServiceProvider;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
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

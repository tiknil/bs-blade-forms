<?php

namespace Tiknil\BsBladeForms\Console;

use Illuminate\Console\Command;

class PublishAssets extends Command
{
    protected $signature = 'bs-blade-forms:publish';

    protected $description = 'Publish bundled css and js files for usage in you project';

    public function handle(): int
    {

        $this->info('Here we are...');

        return self::SUCCESS;
    }
}

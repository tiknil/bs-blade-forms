<?php

namespace Tiknil\BsBladeForms\Components\Traits;

use Illuminate\Support\Collection;

trait WithOptions
{
    public array $options;

    public function setOptions(Collection|array $options): void
    {
        if (is_a($options, Collection::class)) {
            $options = $options->toArray();
        }

        $this->options = $options;

    }
}

<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;

class Form extends BaseComponent
{
    public static mixed $model = null;

    public mixed $prev;

    public function __construct(mixed $model = null, public string $method = 'POST')
    {

        $this->prev = static::$model;

        static::$model = $model;
    }

    public function endModel(): void
    {
        static::$model = $this->prev;
    }

    public function render(): View
    {
        return view('bs-blade-forms::form');
    }
}

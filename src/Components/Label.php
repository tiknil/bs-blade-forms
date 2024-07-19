<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;

class Label extends BaseComponent
{
    public function __construct(public string $name = '', public bool $checkbox = false) {}

    public function render(): View
    {
        return view('bs-blade-forms::label');
    }
}

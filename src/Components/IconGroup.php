<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;

class IconGroup extends BaseComponent
{
    public function __construct(public string $iconClass = '', public string $iconText = '') {}

    public function render(): View
    {
        return view('bs-blade-forms::icon-group');
    }
}

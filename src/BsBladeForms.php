<?php

namespace Tiknil\BsBladeForms;

use Illuminate\Foundation\Vite;

class BsBladeForms
{
    public function assets(): Vite
    {
        return \Vite::useHotFile(base_path('vendor/tiknil/bs-blade-forms/public/vendor/bs-blade-forms/blade-forms.hot'))
            ->useBuildDirectory('vendor/bs-blade-forms')
            ->withEntryPoints(['resources/js/main.ts', 'resources/css/main.scss']);
    }
}

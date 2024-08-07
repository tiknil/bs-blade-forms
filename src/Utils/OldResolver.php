<?php

namespace Tiknil\BsBladeForms\Utils;

use Illuminate\Support\Facades\Session;

class OldResolver
{
    public function solve(string $name): mixed
    {

        $nameFields = $this->splitNameFields($name);

        $oldReference = implode('.', $nameFields);

        return Session::getOldInput($oldReference, null);

    }

    protected function splitNameFields(string $name): mixed
    {
        $fields = [];

        // Identifica ogni gruppo di caratteri diversi da [ e ]
        preg_match_all('/([^(\[\])]+)/x', $name, $fields);

        return $fields[0];
    }
}

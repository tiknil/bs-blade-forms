<?php

namespace Tiknil\BsBladeForms\Components\Traits;

use Tiknil\BsBladeForms\Utils\EnumConverter;

trait WithStringValue
{
    public function parse(mixed $value): ?string
    {
        $value = EnumConverter::enumToValue($value);

        return $value === null ? null : strval($value);
    }
}

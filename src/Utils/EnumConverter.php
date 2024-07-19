<?php

namespace Tiknil\BsBladeForms\Utils;

use BackedEnum;
use UnitEnum;

enum EnumConverter
{
    public static function enumToValue(mixed $value): mixed
    {
        if (is_a($value, BackedEnum::class)) {
            return $value->value;
        } elseif (is_a($value, UnitEnum::class)) {
            return $value->name;
        }

        return $value;

    }
}

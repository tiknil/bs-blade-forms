<?php

use Tiknil\BsBladeForms\Tests\Data\TestBackedEnum;
use Tiknil\BsBladeForms\Tests\Data\TestUnitEnum;
use Tiknil\BsBladeForms\Utils\EnumConverter;

test('it returns null when value is null', function () {
    expect(EnumConverter::enumToValue(null))->toBeNull();
});

test('it returns name when value is unit enum', function () {
    expect(EnumConverter::enumToValue(TestUnitEnum::Value1))->toBe('Value1');
});

test('it returns value when value is backed enum', function () {
    expect(EnumConverter::enumToValue(TestBackedEnum::Value1))->toBe('value_1');
});

test('it returns value when value is string', function () {
    expect(EnumConverter::enumToValue('test'))->toBe('test');
});

test('it returns value when value is number', function () {
    expect(EnumConverter::enumToValue(123))->toBe(123);
});

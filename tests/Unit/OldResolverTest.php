<?php

use Tiknil\BsBladeForms\Utils\OldResolver;

test('it returns null when old is null', function () {
    $resolver = new OldResolver();
    expect($resolver->solve('field'))->toBeNull();
});

test('it solves for multiple mixed data correctly', function () {
    $resolver = new OldResolver();

    Session::flash('_old_input', [
        'field' => 'value',
        'number_field' => 13,
    ]);

    expect($resolver->solve('field'))->toEqual('value')
        ->and($resolver->solve('number_field'))->toEqual(13);
});

test('it solves for nested array data', function () {
    $resolver = new OldResolver();

    Session::flash('_old_input', [
        'field' => [
            'name1' => [
                'name2' => 'value',
            ],
        ],
    ]);

    expect($resolver->solve('field[name1][name2]'))->toEqual('value');
});

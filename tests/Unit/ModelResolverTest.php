<?php

use Illuminate\Database\Eloquent\Model;
use Tiknil\BsBladeForms\Components\Form;
use Tiknil\BsBladeForms\Tests\Data\TestModel;
use Tiknil\BsBladeForms\Utils\ModelResolver;

beforeEach(function () {
    bindModel(null);
});

test('it returns null when model is null', function () {
    $resolver = new ModelResolver;
    expect($resolver->solve('field'))->toBeNull();
});

test('it solves for nested array data', function () {
    $resolver = new ModelResolver;
    Form::$model = [
        'field' => [
            'name1' => [
                'name2' => 'value',
            ],
        ],
    ];

    expect($resolver->solve('field[name1][name2]'))->toEqual('value');
});

test('it solves for nested object data', function () {
    $resolver = new ModelResolver;
    Form::$model = (object) [
        'field' => (object) [
            'name1' => (object) [
                'name2' => 'value',
            ],
        ],
    ];

    expect($resolver->solve('field[name1][name2]'))->toEqual('value');
});

test('it solves for model data correctly', function () {
    $resolver = new ModelResolver;
    $model = Mockery::mock(Model::class);
    $model->shouldReceive('getAttribute')->with('field')->andReturn('value');
    $model->shouldReceive('getHidden')->andReturn([]);

    Form::$model = $model;

    expect($resolver->solve('field'))->toEqual('value');
});

test('it solves null for hidden model attribute', function () {
    $resolver = new ModelResolver;
    $model = new TestModel(['hidden_field' => 'value']);

    Form::$model = $model;

    expect($resolver->solve('hidden_field'))->toBeNull();
});

test('it solves for nested model data correctly', function () {
    $resolver = new ModelResolver;
    $model = Mockery::mock(Model::class);
    $model->shouldReceive('getAttribute')->with('field')->andReturn(['name1' => 'value']);
    $model->shouldReceive('getHidden')->andReturn([]);

    Form::$model = $model;

    expect($resolver->solve('field[name1]'))->toEqual('value');
});

test('it solves for std object', function () {
    $resolver = new ModelResolver;

    $model = new \stdClass;
    $model->name = 'name value';
    $model->data['field'] = 'field value';

    Form::$model = $model;

    expect($resolver->solve('name'))->toEqual('name value')
        ->and($resolver->solve('data[field]'))->toEqual('field value');
});

test('it solves to null for missing field', function () {
    $resolver = new ModelResolver;

    $model = new \stdClass;
    $model->name = 'name value';

    Form::$model = $model;

    expect($resolver->solve('field'))->toBeNull();
});

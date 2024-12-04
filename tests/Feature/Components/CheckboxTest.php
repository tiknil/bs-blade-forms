<?php

use Illuminate\Support\Facades\Session;
use Tiknil\BsBladeForms\Components\Checkbox;
use Tiknil\BsBladeForms\Tests\Data\TestModel;

describe('Resolve initial value', function () {

    it('uses default value', function () {

        $checkbox = new Checkbox(name: 'enabled');

        expect($checkbox->checked)->toBeNull();
    });

    it('reads old input', function () {

        Session::flash('_old_input', [
            'enabled' => '1',
        ]);

        $checkbox = new Checkbox(name: 'enabled');

        expect($checkbox->checked)->toBeTrue();

    });

    it('reads model field', function () {

        $testModel = new TestModel(['enabled' => true]);

        bindModel($testModel);

        $checkbox = new Checkbox(name: 'enabled', checked: false);

        expect($checkbox->checked)->toBeTrue();
    });

    it('gives priority to old', function () {

        $testModel = new TestModel(['enabled' => false]);
        bindModel($testModel);

        Session::flash('_old_input', [
            'enabled' => true,
        ]);

        $checkbox = new Checkbox(name: 'enabled', checked: false);

        expect($checkbox->checked)->toBeTrue();
    });

});

describe('Renders correctly', function () {
    it('renders default', function () {

        $view = $this->blade('<x-bs::checkbox name="enabled" />');

        expect($view)->assertDontSee('checked')
            ->and($view)->toMatchSnapshot();

    });

    it('renders with full options selected', function () {

        $view = $this->blade(
            '<x-bs::checkbox name="enabled" value="a" false-value="b" label="Test enabled" :checked="$checked" wire:model="enabled" />',
            ['checked' => true]
        );

        expect($view)->assertSee('checked')
            ->and($view)->toMatchSnapshot();

    });

    it('renders checked when old is present', function () {

        Session::flash('_old_input', [
            'enabled' => true,
        ]);

        $view = $this->blade(
            '<x-bs::checkbox name="enabled" />',
            ['checked' => true]
        );

        expect($view)->assertSee('checked');
    });

    it('renders checked when model is present', function () {

        $testModel = new TestModel(['enabled' => true]);

        bindModel($testModel);

        $view = $this->blade(
            '<x-bs::checkbox name="enabled" />',
            ['checked' => true]
        );

        expect($view)->assertSee('checked');
    });

});

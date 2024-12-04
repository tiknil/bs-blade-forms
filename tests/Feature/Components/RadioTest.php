<?php

use Illuminate\Support\Facades\Session;
use Tiknil\BsBladeForms\Components\Radio;
use Tiknil\BsBladeForms\Tests\Data\TestBackedEnum;
use Tiknil\BsBladeForms\Tests\Data\TestModel;

describe('Resolve initial value', function () {

    it('uses default value', function () {

        $radio = new Radio(name: 'role', value: 'Admin');

        expect($radio->checked)->toBeNull();
    });

    it('reads old input', function () {

        Session::flash('_old_input', [
            'role' => 'Admin',
        ]);

        $radio = new Radio(name: 'role', value: 'Admin');

        expect($radio->checked)->toBeTrue();

    });

    it('reads model field', function () {

        $testModel = new TestModel(['role' => 'Admin']);
        bindModel($testModel);

        $radio = new Radio(name: 'role', value: 'Admin', checked: false);

        expect($radio->checked)->toBeTrue();
    });

    it('gives priority to old', function () {

        $testModel = new TestModel(['role' => 'User']);
        bindModel($testModel);

        Session::flash('_old_input', [
            'role' => 'Admin',
        ]);

        $radio = new Radio(name: 'role', value: 'Admin', checked: false);

        expect($radio->checked)->toBeTrue();
    });

});

describe('Renders correctly', function () {
    it('renders default', function () {

        $view = $this->blade('<x-bs::radio name="role" value="Admin" />');

        expect($view)->assertDontSee('checked')
            ->and($view)->toMatchSnapshot();

    });

    it('renders with full options selected', function () {

        $view = $this->blade(
            '<x-bs::radio name="role" value="Admin" label="Administrator" :checked="$checked" />',
            ['checked' => true]
        );

        expect($view)->assertSee('checked')
            ->and($view)->toMatchSnapshot();

    });

    it('renders checked when old is present', function () {

        Session::flash('_old_input', [
            'role' => 'Admin',
        ]);

        $view = $this->blade(
            '<x-bs::radio name="role" value="Admin" />',
            ['checked' => true]
        );

        expect($view)->assertSee('checked');
    });

    it('renders checked when model is present', function () {

        $testModel = new TestModel(['role' => 'Admin']);
        bindModel($testModel);

        $view = $this->blade(
            '<x-bs::radio name="role" value="Admin" />',
            ['checked' => true]
        );

        expect($view)->assertSee('checked');
    });

    it('supports enum values - true comparison', function () {

        $testModel = new TestModel(['role' => TestBackedEnum::Value1]);
        bindModel($testModel);

        $view = $this->blade(
            '<x-bs::radio name="role" :value="$role" />',
            ['checked' => true, 'role' => TestBackedEnum::Value1]
        );

        expect($view)->assertSee('checked');
    });

    it('supports enum values - false comparison', function () {

        $testModel = new TestModel(['role' => TestBackedEnum::Value1]);
        bindModel($testModel);

        $view = $this->blade(
            '<x-bs::radio name="role" :value="$role" />',
            ['checked' => true, 'role' => TestBackedEnum::Value2]
        );

        expect($view)->assertDontSee('checked');
    });

});

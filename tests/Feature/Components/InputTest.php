<?php

use Illuminate\Support\Facades\Session;
use Tiknil\BsBladeForms\Components\Input;
use Tiknil\BsBladeForms\Tests\Data\TestModel;

describe('Resolve initial value', function () {

    it('uses default value', function () {

        $input = new Input(name: 'email');

        expect($input->value)->toBeNull();
    });

    it('reads old input', function () {

        Session::flash('_old_input', [
            'email' => 'test@example.com',
        ]);

        $input = new Input(name: 'email');

        expect($input->value)->toBe('test@example.com');

    });

    it('reads model field', function () {

        $testModel = new TestModel(['email' => 'test@example.com']);
        bindModel($testModel);

        $input = new Input(name: 'email');

        expect($input->value)->toBe('test@example.com');
    });

    it('gives priority to old', function () {

        $testModel = new TestModel(['email' => 'test_model@example.com']);
        bindModel($testModel);

        Session::flash('_old_input', [
            'email' => 'test_old@example.com',
        ]);

        $input = new Input(name: 'email');

        expect($input->value)->toBe('test_old@example.com');
    });

    it('gives priority to specified value', function () {

        $testModel = new TestModel(['email' => 'test_model@example.com']);
        bindModel($testModel);

        Session::flash('_old_input', [
            'email' => 'test_old@example.com',
        ]);

        $input = new Input(name: 'email', value: 'test@example.com');

        expect($input->value)->toBe('test@example.com');
    });

});

describe('Renders correctly', function () {
    it('renders default', function () {

        $view = $this->blade('<x-bs::input name="email" value="test@example.com" />');

        expect($view)->assertDontSee('label')
            ->and($view)->toMatchSnapshot();

    });

    it('renders with full options selected', function () {

        $view = $this->blade(
            '<x-bs::input type="email" name="email" value="test@example.org" label="Email" icon="bi bi-envelope" required />',
        );

        expect($view)->toMatchSnapshot();

    });

    it('renders old value when old is present', function () {

        Session::flash('_old_input', [
            'email' => 'test@example.com',
        ]);

        $view = $this->blade(
            '<x-bs::input name="email" />',
        );

        expect($view)->assertSee('value="test@example.com"', false);
    });

    it('renders model field when model is present', function () {

        $testModel = new TestModel(['email' => 'test@example.com']);
        bindModel($testModel);

        $view = $this->blade(
            '<x-bs::input name="email" />'
        );

        expect($view)->assertSee('value="test@example.com"', false);
    });

});

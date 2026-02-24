<?php

use Illuminate\Support\Facades\Session;
use Tiknil\BsBladeForms\Components\Autocomplete;
use Tiknil\BsBladeForms\Tests\Data\TestModel;

include_once 'tests/Data/TestOptions.php';

describe('Resolve initial value', function () {

    it('uses default value', function () {
        $input = new Autocomplete(name: 'country', options: TEST_OPTIONS);
        expect($input->value)->toBeNull();
    });

    it('supports collection options', function () {
        $input = new Autocomplete(name: 'country', options: collect(TEST_OPTIONS));
        expect($input->options)->toEqual(TEST_OPTIONS);
    });

    it('reads old input', function () {
        Session::flash('_old_input', [
            'country' => 'it',
        ]);

        $input = new Autocomplete(name: 'country', options: TEST_OPTIONS);
        expect($input->value)->toBe('it');
    });

    it('reads model field', function () {
        $testModel = new TestModel(['country' => 'us']);
        bindModel($testModel);

        $input = new Autocomplete(name: 'country', options: TEST_OPTIONS);
        expect($input->value)->toBe('us');
    });

    it('gives priority to old', function () {
        $testModel = new TestModel(['country' => 'us']);
        bindModel($testModel);

        Session::flash('_old_input', [
            'country' => 'it',
        ]);

        $input = new Autocomplete(name: 'country', value: 'de', options: TEST_OPTIONS);
        expect($input->value)->toBe('it');
    });

    it('gives priority to value', function () {
        $testModel = new TestModel(['country' => 'us']);
        bindModel($testModel);

        $input = new Autocomplete(name: 'country', value: 'de', options: TEST_OPTIONS);
        expect($input->value)->toBe('de');
    });
});

describe('Renders correctly', function () {
    it('renders default', function () {
        $view = $this->blade(
            '<x-bs::autocomplete name="country" :options="$options" />',
            ['options' => TEST_OPTIONS]
        );

        expect($view)
            ->assertDontSee('<label', false)
            ->assertSee('class="ac-wrapper"', false)
            ->and($view)->toMatchSnapshot();
    });

    it('renders with full options', function () {
        $view = $this->blade(
            '<x-bs::autocomplete 
                name="country" 
                value="it" 
                :options="$options" 
                label="Country" 
                icon="bi bi-map" 
                placeholder="Start typing..." 
                allow-clear 
                empty-value="0" 
                allow-custom-values
            />',
            ['options' => TEST_OPTIONS]
        );

        expect($view)
            ->assertSee('<label', false)
            ->assertSee('class="ac-wrapper"', false)
            ->assertSee('<option value="it" selected', false)
            ->assertSee('data-allow-custom', false)
            ->and($view)->toMatchSnapshot();
    });

    it('renders with fetch url', function () {
        $view = $this->blade(
            '<x-bs::autocomplete 
                name="country" 
                fetch-url="https://fetch.url" 
            />',
            ['options' => TEST_OPTIONS]
        );

        expect($view)
            ->assertSee('data-fetchurl="https://fetch.url"', false)
            ->and($view)->toMatchSnapshot();
    });

    it('renders old value when old is present', function () {
        Session::flash('_old_input', [
            'country' => 'it',
        ]);

        $view = $this->blade(
            '<x-bs::autocomplete name="country" :options="$options" />',
            ['options' => TEST_OPTIONS]
        );

        expect($view)
            ->assertSee('<option value="it" selected', false)
            ->and($view)->toMatchSnapshot();
    });
});

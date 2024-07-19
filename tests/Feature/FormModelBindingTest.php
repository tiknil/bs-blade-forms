<?php

use Tiknil\BsBladeForms\Tests\Data\TestModel;

include_once 'tests/Data/TestOptions.php';

it('supports complex model binding on nested form', function () {

    $user = new TestModel([
        'email' => 'test@example.org',
        'role' => 'Admin',
        'hidden_field' => 'secret',
        'address' => [
            'country' => 'it',
            'city' => 'Pedrengo',
            'zipCode' => '24066',
            'province' => 'BG',

            'street' => 'Viale Kennedy',
            'number' => '21',
        ],

        'enabled_countries' => ['it', 'de'],
    ]);

    $view = $this->blade(
        <<<'HTML'
            <x-bs::form method="PATCH" action="url" :model="$user">
                <x-bs::input type="email" name="email" required />
                <x-bs::input name="hidden_field" type="password" required />

                <x-bs::radio name="role" value="User" label="User" />
                <x-bs::radio name="role" value="Admin" label="Admin" />

                <x-bs::search-select name="address[country]" :options="$countries" />

                <x-bs::input type="text" name="address[city]"  />
                <x-bs::input type="text" name="address[zipCode]"  />
                <x-bs::input type="text" name="address[province]"  />
                <x-bs::input type="text" name="address[street]"  />
                <x-bs::input type="number" name="address[number]"  />

                <x-bs::multi-select name="enabled_countries" :options="$countries" />

            </x-bs::form>
            HTML, ['user' => $user, 'countries' => TEST_OPTIONS]);

    expect($view)->assertDontSee('secret')
        ->assertSee('test@example.org')
        ->assertSee('Pedrengo')
        ->toMatchSnapshot();
});

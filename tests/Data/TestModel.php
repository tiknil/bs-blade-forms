<?php

namespace Tiknil\BsBladeForms\Tests\Data;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    // Model can be created with whatever datas
    protected $guarded = [];

    protected $hidden = ['hidden_field'];
}

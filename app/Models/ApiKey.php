<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model {
    protected $table = 'api_key';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'api_key',
    ];
}
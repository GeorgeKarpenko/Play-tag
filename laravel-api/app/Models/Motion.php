<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motion extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id', 'motion'
    ];

}

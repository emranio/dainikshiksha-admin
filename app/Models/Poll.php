<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'polls';

    // casts array to json on column poll_options
    protected $casts = [
        'poll_options' => 'array',
    ];

}

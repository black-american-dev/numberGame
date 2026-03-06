<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    //protected $table = 'attempts'; // matches your typo in migration
    protected $fillable = ['game_id', 'guessed_numbers', 'result'];
    protected $casts = [
        'guessed_numbers' => 'array',
        'result' => 'array'
    ];

    public function game() {
        return $this->belongsTo(Game::class, "game_id");
    }
}

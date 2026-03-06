<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    //
    //protected $table = "games";
    protected $fillable = ["user_id", 'secret_numbers', 'status', 'difficulty', 'max_attempts'];
    protected $casts = ["secret_numbers" => "array"];

    public function users() {
        return $this->belongsTo(GameUser::class, "user_id");
    }
    public function attempts() {
        return $this->hasMany(Attempt::class, "game_id");
    }
}

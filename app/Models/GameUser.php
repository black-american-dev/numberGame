<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameUser extends Model
{
    //
    protected $table = "game_users";
    protected $fillable = ["name","email","password"];

    public function games() {
        return $this->hasMany(Game::class, "user_id");
    }
}

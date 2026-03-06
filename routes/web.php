<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\numberGameController;
use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class, "checkAge"]);

Route::match(["get","post"], "/number/game/guest", [HomeController::class, "advancedNumberGame"]);


Route::middleware('auth.game')->group(function () {
    Route::get("/number/game/pick", [numberGameController::class, "pickDifficulty"]);
    Route::match(["get","post"], "/number/game", [numberGameController::class, "advancedNumberGame"]);
    Route::get("/logout", [LoginController::class, "logOut"]);
});

Route::get("/login", [LoginController::class, "create"]);
Route::post("/login/submit", [LoginController::class, "login"]);

Route::get("/signup", [LoginController::class, "showSignup"]);
Route::post("/signup", [LoginController::class, "signUp"]);


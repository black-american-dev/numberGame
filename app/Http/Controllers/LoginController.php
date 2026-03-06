<?php

namespace App\Http\Controllers;

use App\Models\GameUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //
    public function create() {
        return view("login");
    }
    public function showSignup() {
        return view("signup");
    }
    public function signUp(Request $request) {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        if (!$password || !$email || !$name) {
            return back();
        }
        GameUser::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
        ]);
        return view("login");
    }
    public function login(Request $request) {
        $email = $request->email;
        $password = $request->password;

        $user = GameUser::where("email", $email)->first();

        if (!$user) {
                return back()->withInput()->withErrors([
                "email" => "No account found with this email !",
            ]);
        }

        if (!Hash::check($password, $user->password)) {
                return back()->withErrors([
                "password" => "Password is invalid !",
            ]);
        }

        session(["user_id" => $user->id]);
        return redirect("/number/game/pick");
    }
    public function logOut() {
        session()->forget("user_id");
        return redirect("login");
    }
}

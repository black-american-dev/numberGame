<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /*
    public function checkAge(Request $request) {
        
        $age = $request->age;
        $username = $request->username;
        $checked = "";
        if($age >= 18) {
            $checked = "majore";
        }else {
            $checked = "minore";
        }

        return view("welcome",[
            "checked"=>$checked,
            "username"=>$username,
            "age"=>$age
        ]);
    }
    public function hundle(Request $request) {
        return match($request->method()) {
            "GET" => view("create"),
            "POST" => $this->store($request),
        };
    }

    public function store ($request) {
        $email = $request->email;
        $password = $request->password;
        return view("store", ["email" => $email, "password" => $password]);
    }
    
    public function numberGame (Request $request) {
        if ($request->isMethod("get")) {
            $number = rand(1,10);
            session(["number" => $number]);
            return view("numberGame");
        }

        if ($request->isMethod("post")) {
            $gessedNumber = $request->gessedNumber;
            if ($gessedNumber > session("number")) {
                return view("numberGame", [ "status" => "error", "message" => "the number you choose is bigger"]);
            }
            if ($gessedNumber < session("number")) {
                return view("numberGame", [ "status" => "error", "message" => "the number you choose is smaller"]);
            }else {
                $correctNumber = session("number");
                session()->forget("number");
                return view("numberGame", [ "status" => "correct" ,"message" => "correct ,the number is :" . $correctNumber]);
            }
        }
    }
    */

    public function advancedNumberGame (Request $request) {
        if ($request->isMethod("get")) {
            $listNumbers = [rand(1,9),rand(1,9),rand(1,9),rand(1,9)];
            $historyList = [];
            session(["listNumbers" => $listNumbers]);
            session(["history" => $historyList]);
            return view("numberGameGuest",[
                "listNumbers" => $listNumbers,
                "history" => []
            ]);
        }
        if($request->isMethod("post")) {
            $guessedListOfNumbers = [$request->first,$request->second,$request->third,$request->fourth];
            $result = [];
            $listNumbers = session("listNumbers");
            $temList = $listNumbers;
            foreach($guessedListOfNumbers as $index => $guessedNumber) {
                if ($listNumbers[$index] == $guessedNumber) {
                    $result [$index] = "green";
                    $temList[$index] = null;
                }
            }
            foreach($guessedListOfNumbers as $index => $guessedNumber) {
                if (!isset($result[$index])) {
                    $foundIndex = array_search($guessedNumber, $temList);
                    if ($foundIndex !== false) {
                        $result [$index] = "orange";
                        $temList[$foundIndex] = null;
                    }
                    else {
                        $result [$index] = "none";
                    }
                }
            }
            $check = array_count_values($result);
            if (($check["green"] ?? 0) == 4) {
                $won = true;
            }
            else {
                $won = false;
            }
            $history = session("history", []);
            $attemp = [$guessedListOfNumbers, $result];
            $history [] = $attemp;
            session(["history" => $history]);
            return view("numberGameGuest", [
                "listNumbers" => session("listNumbers"),
                "style" => $result,
                "won" => $won,
                "history" => $history
            ]);
        }
    }
}

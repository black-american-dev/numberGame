<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Game;
use App\Models\GameUser;
use Illuminate\Http\Request;

class numberGameController extends Controller
{
    //
    public function pickDifficulty() {
        $user = GameUser::find(session('user_id'));
        $allGames = $user->games()->with('attempts')->get();
        return view("numberGame", [
            'user'     => $user,
            'allGames' => $allGames,
            'history'  => [],
        ]);
    }

    public function advancedNumberGame (Request $request) {
        $user = GameUser::find(session('user_id'));
        if ($request->isMethod("get")) {

            if(session("game_id")) {
                $old = Game::find(session("game_id"));
                if ($old && $old->status === "playing") {
                    $old->update(["status" => "lost"]);
                }
            }
        
            $difficulty = $request->query("difficulty", "easy");

            $length = match($difficulty) {
                "medium" => 7,
                "hard" => 10,
                default => 5
            };

            $maxAttempts = match($difficulty) {
                "medium" => 7,
                "hard" => 10,
                default => 5
            };
            $listNumbers = array_map(fn() => rand(1,9), array_fill(0, $length, null));
            
            $game = Game::create([
                'user_id' => session('user_id'),
                'secret_numbers' => $listNumbers,
                'difficulty' => $difficulty,
                'max_attempts' => $maxAttempts,
                'status' => 'playing',
            ]);
            $allGames = $user->games()->with('attempts')->get();
            session(["game_id"=> $game->id]);
            return view("numberGame",[
                'user'        => $user,
                'currentGame' => $game,
                'allGames'    => $allGames,
                "listNumbers" => $listNumbers,
                'difficulty'  => $game->difficulty,
                'maxAttempts' => $game->max_attempts,
                "history" => []
            ]);
        }
        if($request->isMethod("post")) {
            if (!session('user_id')) {
                return redirect('/login');
            }
            $game = Game::find(session("game_id"));
            $result = [];
            $listNumbers = $game->secret_numbers;
            $tempList = $listNumbers;

            $inputNames = ['first','second','third','fourth','fifth','sixth','seventh','eighth','ninth','tenth'];
            $guessedListOfNumbers = array_map(
                fn($name) => $request->$name,
                array_slice($inputNames, 0 , count($listNumbers))
            );
            foreach($guessedListOfNumbers as $index => $guessedNumber) {
                if ($listNumbers[$index] == $guessedNumber) {
                    $result [$index] = "green";
                    $tempList[$index] = null;
                }
            }
            foreach($guessedListOfNumbers as $index => $guessedNumber) {
                if (!isset($result[$index])) {
                    $foundIndex = array_search($guessedNumber, $tempList);
                    if ($foundIndex !== false) {
                        $result [$index] = "orange";
                        $tempList[$foundIndex] = null;
                    }
                    else {
                        $result [$index] = "none";
                    }
                }
            }
            Attempt::create([
                "game_id" => $game->id,
                "guessed_numbers" => $guessedListOfNumbers,
                "result" => $result
            ]);
            $check = array_count_values($result);
            if (($check["green"] ?? 0) == count($listNumbers)) {
                $won = true;
                $game->update(["status" => "won"]);
                session()->forget('game_id');
            }
            else {
                $won = false;
                $history = $game->attempts->map(fn($a) => [$a->guessed_numbers, $a->result]);
                if(count($history) >= $game->max_attempts) {
                    $game->update(["status" => "lost"]);
                    session()->forget('game_id');
                }
            }
            $history = $game->attempts->map(fn($a) => [$a->guessed_numbers, $a->result]);
            $allGames = $user->games()->with('attempts')->get();

            return view("numberGame", [
                'user'        => $user,
                'currentGame' => $game,
                'allGames'    => $allGames,
                'listNumbers' => $listNumbers,
                'difficulty'  => $game->difficulty,
                'maxAttempts' => $game->max_attempts,
                'style'       => $result,
                'won'         => $won,
                'history'     => $history,
            ]);
        }
    }
}

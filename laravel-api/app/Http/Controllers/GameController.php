<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{
    Game,
    User
};

use App\Events\{
    NewMove,
    StartGame
};

class GameController extends Controller
{

    public function new_move(Request $request){
        $field = $this->field_empty_cell($request['field']);
        event(new NewMove($field));
    }

    public function start_game(Request $request){
        $field = $this->field_empty_cell($request['field']);
        event(new StartGame($field));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($user_id, $game_id)
    {
        $game = Game::where([
            ['user_id', '=', $user_id],
            ['id', '=', $game_id]
        ])->firstOrFail();
        return response()->json($game); 
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request['user'];
        if(!$request['field']){
            $field = $this->shuffle_right(range(0,14));
            $field []= '';
        }
        else{
            $field = $this->field_empty_cell($request['field']);
        }
        $game = Game::create([
            'user_id' => $user['id']
        ]);

        return response()->json([
            'game' => $game,
            'field' => $field
        ]); 
    }

    public function solve($id, Request $request){
        if($this->checking_the_solution($request['motions'])){
            $game = Game::findOrFail($id);
            $game->update(['time' => \Carbon\Carbon::parse($request['seconds'])->format('H:i:s')]);
            return response()->json([
                'game' => $game,
                'number_of_moves' => count($request['motions']) - 1
            ]); 
        }
        return response()->json([
            'error' => 'Вы читер'
        ], 400);
    }

    private function shuffle_right($array) {
        $array = collect($array)->shuffle();
        $array = $array->all();
        $k = 0;
        for ($i = 0; $i < count($array) - 1; $i++) { 
            for ($j = $i + 1; $j < count($array); $j++) { 
                if ($array[$i] > $array[$j]) {
                  $k += 1;
                }
            }
        }
        if ($k % 2 !== 0) {
            return $this->shuffle_right($array);
        }
        return $array;
    }

    private function checking_the_solution($array){
        $field = array_shift($array);
        foreach ($field as $key => $value) {
            if(gettype($value) === "NULL"){
                $empty_cell = $key;
                break;
            }
        }
        return $this->motions($field, $empty_cell, $array);
    }

    private function motions($field, $empty_cell, $motions){
        if(count($motions)){
            if($this->movable_cell($empty_cell, $motions[0])){
                [$field[$empty_cell], $field[$motions[0]]] = [$field[$motions[0]], $field[$empty_cell]];
                $empty_cell = $motions[0];
                array_shift($motions);
                return $this->motions($field, $empty_cell, $motions);
            }
        } else {
            if(json_encode($field) === json_encode([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,null])){
                return true;
            }
        }
        return false;
    }

    private function movable_cell($empty_cell, $movable, $number_of_rows = 4) {
        $poverca = intdiv($empty_cell, $number_of_rows);
        if ($empty_cell - 1 === $movable ||
            $empty_cell + 1 === $movable ||
            ($poverca - 1) * $number_of_rows + $empty_cell % $number_of_rows === $movable ||
            ($poverca + 1) * $number_of_rows + $empty_cell % $number_of_rows === $movable){
            return true;
        }
        return false;
    }

    private function field_empty_cell($field){
        
        foreach ($field as $key => $value) {
            if(gettype($value) === "NULL"){
                $field[$key] = '';
                break;
            }
        }
        return $field;
    }
}

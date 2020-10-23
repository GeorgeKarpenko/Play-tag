<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GameSolveRequest;

use App\Models\{
    User,
    Game
};

use App\Events\{
    NewMove,
    StartGame
};

class GameController extends Controller
{

    public function new_move($user_id, $game_id, Request $request){
        $field = $this->field_empty_cell($request['field']);
        event(new NewMove($field, $request['seconds'], $game_id));
    }

    public function start_game($user_id, $game_id, Request $request){
        $field = $this->field_empty_cell($request['field']);
        event(new StartGame($field, $game_id));
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
        $field = $this->field($request['field']);
        $game = Game::create([
            'user_id' => $user['id'],
            'starting_position' => json_encode($field)
        ]);

        return response()->json([
            'game' => $game,
            'field' => $field
        ]); 
    }

    public function update(Game $game, GameSolveRequest $request){
        $game->update([
            'time' => \Carbon\Carbon::parse($request['seconds'])->format('H:i:s')
        ]);

        $this->motions_save($game, $request);

        return response()->json([
            'game' => $game,
            'number_of_moves' => count($request['motions']) - 1
        ]); 
        return response()->json([
            'error' => 'Вы читер'
        ], 400);
    } 

}

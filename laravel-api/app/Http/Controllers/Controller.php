<?php

namespace App\Http\Controllers;

use App\Models\Motion;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected function shuffle_right($array) {
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


    protected function field_empty_cell($field){
        
        foreach ($field as $key => $value) {
            if(gettype($value) === "NULL"){
                $field[$key] = '';
                break;
            }
        }
        return $field;
    }

    protected function field($field) {
        if(!$field){
            $field = $this->shuffle_right(range(0,14));
            $field []= '';
        }
        else{
            $field = $this->field_empty_cell($field);
        }
        return $field;
    }

    protected function motions_save($game, $request)
    {
        $motions_db = [];
        for ($i=1; $i < count($request['motions']); $i++) { 
            $motions_db []= [
                'game_id' => $game->id,
                'motion' => $request['motions'][$i],
            ];
        }
        Motion::insert($motions_db);
    }
  
}

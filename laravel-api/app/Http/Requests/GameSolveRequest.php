<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class GameSolveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            // 'motions' => $this->checking_the_solution($request['motions'])
        ];
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
}

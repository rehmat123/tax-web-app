<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public $successStatus = 200;
    public function getAll(){
        $question = Question::where('user_id','=',auth('api')->user()->id)->get();
        if($question){
            return response()->json(['success' => $question], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }

     }
     public function login(){
        // if(Auth::attempt(['email' => request('username'), 'password' => request('password')])){
        //     $user = Auth::user();
        //     $success['token'] =  $user->createToken('MyApp')-> accessToken;
        //     $success['user'] = $user;
        //     return response()->json(['success' => $success], $this-> successStatus);
        // }
        // else{
        //     return response()->json(['error'=>'Unauthorised'], 401);
        // }
    }
}

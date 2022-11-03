<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Heard;
use Validator;


class HeardController extends Controller
{
     public function create(Request $request)
     {

        $validator = Validator::make($request->all(), [
        'user_id' => 'required',   
        'enterprise_id' => 'required',     
        'heard_name' => 'required',        

        ]);

      if ($validator->fails()) {
         $errors = $validator->errors();
         return response()->json(['error' => $errors->toJson()]);
      }
       
       Heard::insert([
        'user_id'=>$request->user_id,
        'enterprise_id'=>$request->enterprise_id,
        'heard_name'=>$request->heard_name,   
        'heard_description'=>$request->heard_description,        

      ]);

           return response()->json(['message' => 'Created successfully']); 


     }

     public function heardList(Request $request){

         $user_id = $request->query('user_id');
         $enterprise_id = $request->query('enterprise_id');
         
         $heard = Heard::where('user_id',$user_id)
         ->where('enterprise_id',$enterprise_id)
         ->get();
          

           return response()->json(['heard' => $heard]);

     }
}

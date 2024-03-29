<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MeatRecord;
use App\Models\WoolProductionRecord;
use App\Models\Enterprise;
use App\Models\User;
use Validator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class MeatRecordController extends Controller
{
  
  public function create_daily_bird_record(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'date' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response()->json(['error' => $errors->toJson()]);
    }

    try 
    {
        $obj = new MeatRecord;
        $obj->date = $request->date;
        $obj->dead = $request->dead;
        $obj->removed = $request->removed;
        $obj->sold = $request->sold;
        $obj->farm_consumption = $request->farm_consumption;
        $obj->enterprise_id = $request->enterprise_id;
        $obj->bird_record  = 1;
        $obj->status = 1;
        $obj->user_id = Auth::user()->id;
        $obj->created_by = Auth::user()->id;
        $obj->created_at = Carbon::now();
        $obj->save();

      return response()->json(['response' => ['status' => true, 'message' => 'Record Added successfully']], 
        JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      // return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    
  }

  public function create_daily_production_record(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'date' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response()->json(['error' => $errors->toJson()]);
    }

    try 
    {
        $obj = new MeatRecord;
        $obj->date = $request->date;
        $obj->dead = $request->dead;
        $obj->removed = $request->removed;
        $obj->sold = $request->sold;
        $obj->remarks = $request->remarks;
        $obj->enterprise_id = $request->enterprise_id;
        $obj->production_record  = 1;
        $obj->status = 1;
        $obj->user_id = Auth::user()->id;
        $obj->created_by = Auth::user()->id;
        $obj->created_at = Carbon::now();
        $obj->save();

      return response()->json(['response' => ['status' => true, 'message' => 'Record Added successfully']], 
        JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      // return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    
  }

  public function create_meat_slaughter_record(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'date' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response()->json(['error' => $errors->toJson()]);
    }

    try 
    {
        $obj = new MeatRecord;
        $obj->date = $request->date;
        $obj->animal_id = $request->animal_id;
        $obj->no_of_birds = $request->no_of_birds;
        $obj->kill_weight = $request->kill_weight;
        $obj->dressed_weight = $request->dressed_weight;
        $obj->quality = $request->quality;
        $obj->enterprise_id = $request->enterprise_id;
        $obj->slaughter_record  = 1;
        $obj->status = 1;
        $obj->user_id = Auth::user()->id;
        $obj->created_by = Auth::user()->id;
        $obj->created_at = Carbon::now();
        $obj->save();

      return response()->json(['response' => ['status' => true, 'message' => 'Record Added successfully']], 
        JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      // return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    
  }

  public function create_wool_production_record(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'date' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response()->json(['error' => $errors->toJson()]);
    }

    try 
    {
        $obj = new WoolProductionRecord;
        $obj->date = $request->date;
        $obj->animal_id = $request->animal_id;
        $obj->weight = $request->weight;
        $obj->quality = $request->quality;
        $obj->enterprise_id = $request->enterprise_id;
        $obj->status = 1;
        $obj->user_id = Auth::user()->id;
        $obj->created_by = Auth::user()->id;
        $obj->created_at = Carbon::now();
        $obj->save();

      return response()->json(['response' => ['status' => true, 'message' => 'Record Added successfully']], 
        JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      // return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    
  }

  public function get_daily_bird_record(Request $request)
  {
    try 
    {
      $bird_record =  MeatRecord::from('meat_records as f')
                      ->leftjoin('enterprise as e', 'e.id', 'f.enterprise_id')
                        ->leftjoin('users as u', 'u.id', 'f.user_id')
                      ->where('f.user_id',$request->user_id)->where('f.enterprise_id',$request->enterprise_id)
                      ->where('f.bird_record',1)
                      ->select('f.id','f.date','f.dead','f.removed','f.sold','f.farm_consumption','f.status','e.enterprise_name','u.name as username',
                      'f.created_at','f.updated_at')
                      ->get();

      return response()->json(['response' => ['status' => true,'data' => $bird_record]],JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    
  }

  public function get_daily_production_record(Request $request)
  {
    try 
    {
      $production_record =  MeatRecord::from('meat_records as f')
                    ->leftjoin('enterprise as e', 'e.id', 'f.enterprise_id')
                        ->leftjoin('users as u', 'u.id', 'f.user_id')
                    ->where('f.user_id',$request->user_id)->where('f.enterprise_id',$request->enterprise_id)
                    ->where('f.production_record',1)
                      ->select('f.id','f.date','f.dead','f.removed','f.sold','f.remarks','f.status','e.enterprise_name','u.name as username',
                      'f.created_at','f.updated_at')
                      ->get();

      return response()->json(['response' => ['status' => true,'data' => $production_record]],JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    
  }

  public function get_meat_slaughter_record(Request $request)
  {
    try 
    {
      $slaughter_record =  MeatRecord::from('meat_records as f')
                      ->leftjoin('enterprise as e', 'e.id', 'f.enterprise_id')
                        ->leftjoin('users as u', 'u.id', 'f.user_id')
                        ->leftjoin('animals as a', 'a.id', 'f.animal_id')
                      ->where('f.user_id',$request->user_id)->where('f.enterprise_id',$request->enterprise_id)
                      ->where('f.slaughter_record',1)
                      ->select('f.id','f.date','f.animal_id','f.no_of_birds','f.kill_weight','f.dressed_weight','f.quality','f.status','e.enterprise_name','u.name as username', 'a.animal_name','f.created_at','f.updated_at')
                      ->get();

      return response()->json(['response' => ['status' => true,'data' => $slaughter_record]],JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    
  }

  public function get_wool_production_record(Request $request)
  {
    try 
    {
      $wool_record =  WoolProductionRecord::from('wool_production_records as f')
                      ->leftjoin('enterprise as e', 'e.id', 'f.enterprise_id')
                        ->leftjoin('users as u', 'u.id', 'f.user_id')
                        ->leftjoin('animals as a', 'a.id', 'f.animal_id')
                      ->where('f.user_id',$request->user_id)->where('f.enterprise_id',$request->enterprise_id)
                      ->select('f.id','f.date','f.animal_id','f.weight','f.quality','f.status','e.enterprise_name','u.name as username', 'a.animal_name',
                      'f.created_at','f.updated_at')
                      ->get();

      return response()->json(['response' => ['status' => true,'data' => $wool_record]],JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    
  }
  
  
}

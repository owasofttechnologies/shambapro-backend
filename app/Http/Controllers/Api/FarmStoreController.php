<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmStore;
use App\Models\FarmStoreCategory;
use App\Models\FarmStoreSubCategory;
use App\Models\FarmStoreType;
use App\Models\FarmStoreHistory;
use App\Models\User;
use Validator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class FarmStoreController extends Controller
{

  public function farm_store_type()
  {
    try 
    {
      $t_type =  FarmStoreType::get();
      return response()->json(['response' => ['status' => true, 'data' => $t_type]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => 'Something went wrong!']], JsonResponse::HTTP_BAD_REQUEST);
    }  

  }

  public function farm_store_category($type)
  {
    try 
    {
      $t_cat =  FarmStoreCategory::where('type',$type)->get();
      return response()->json(['response' => ['status' => true, 'data' => $t_cat]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => 'Something went wrong!']], JsonResponse::HTTP_BAD_REQUEST);
    }  

  }

   public function farm_store_subcategory($category_id)
  {
    try 
    {
      $t_scat =  FarmStoreSubCategory::where('category_id',$category_id)->get();
      return response()->json(['response' => ['status' => true, 'data' => $t_scat]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => 'Something went wrong!']], JsonResponse::HTTP_BAD_REQUEST);
    }  

  }

  public function create(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'date' => 'required',
      'name' => 'required',
      'price' => 'required',
      'source' => 'required',
      'category_id' => 'required'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response()->json(['error' => $errors->toJson()]);
    }

    try 
    {
      $type =  FarmStoreType::where('id',$request->type_id)->first();
     
      $t_cat =  FarmStoreCategory::where('type',$request->type_id)->get();

      
        
        $obj = new FarmStore;
          
        $obj->date = $request->date;
        $obj->name = $request->name;
        $obj->price = $request->price;
        $obj->source = $request->source;
        $obj->category_id = $request->category_id;
        
        if($type->id == 5)
        {
          $obj->size = $request->size;
        }  
        
        if($type->id == 6 || $type->id == 2)
        {
          $obj->description = $request->description;
          $obj->quantity = $request->quantity;
        }

        if($type->id == 2)
        {
          $obj->expiry_date = $request->expiry_date;
          $obj->subcategory_id = $request->subcategory_id;
          $obj->enterprise_id = $request->enterprise_id;
        }  

        if($type->id != 5 && $type->id != 2)
        {
          $obj->condition = $request->condition;
        }  
        $obj->type = $request->type_id;
        $obj->status = 1; 
        $obj->user_id = Auth::user()->id;
        $obj->createdby = Auth::user()->id;
        $obj->created_at = Carbon::now();
        $obj->save();

        if($type->id == 2)
        {
          $history = new FarmStoreHistory;
          $history->farm_store_id = $obj->id;
          $history->quantity = $request->quantity;
          $history->created_by = Auth::user()->id;
          $history->created_at = Carbon::now();
          $history->save();
        }  
        
      return response()->json(['response' => ['status' => true, 'message' => 'Record Added successfully']], 
        JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      // return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    

  }

  public function index(Request $request)
  {
    try
    {
      if($request->type_id == 2) 
      {
        $farmstore = FarmStore::from('farm_store as fs')
                      ->join('farm_store_category as fsc', 'fs.category_id',  'fsc.id') 
                      ->join('farm_store_type as fst', 'fsc.type',  'fst.id')
                      ->join('farm_store_subcategory as fss', 'fs.subcategory_id',  'fss.id')
                      ->leftjoin('enterprise as et', 'fs.enterprise_id',  'et.id')
                      ->where('fs.type', $request->type_id)
                      ->where('fs.category_id', $request->category_id)
                      ->where('fs.subcategory_id', $request->subcategory_id)
                      ->where('fs.enterprise_id', $request->enterprise_id)
                      ->where('fs.user_id', Auth::user()->id)
                      ->where('fs.status', 1)
                      ->select(
                      'et.enterprise_name as enterpriseName',  
                      'fst.farm_type as farmType',
                      'fsc.farm_cat as categoryName',
                      'fss.farm_subcat as subCategoryName',
                      'fs.*'
                      )  
                      ->get(); 
      }
      else
      {
        $farmstore = FarmStore::from('farm_store as fs')
                      ->join('farm_store_category as fsc', 'fs.category_id',  'fsc.id') 
                      ->join('farm_store_type as fst', 'fsc.type',  'fst.id')
                      ->where('fs.type', $request->type_id)
                      ->where('fs.category_id', $request->category_id)
                      ->where('fs.user_id', Auth::user()->id)
                      ->where('fs.status', 1)
                      ->select(
                      'fsc.farm_cat as categoryName',
                      'fst.farm_type as farmType',
                      'fs.*'
                      )  
                      ->get(); 

      }  
            
      return response()->json(['response' => ['status' => true, 'data' => $farmstore]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }  
  }

  public function edit($id)
  {
    try
    {
      $farm = FarmStore::where('id', $id)->first();
      if($farm->type == 2)
      {

        $farmstore = FarmStore::from('farm_store as fs')
                    ->join('farm_store_category as fsc', 'fs.category_id',  'fsc.id') 
                    ->join('farm_store_type as fst', 'fsc.type',  'fst.id')
                    ->leftjoin('farm_store_subcategory as fss', 'fs.subcategory_id',  'fss.id')
                    ->leftjoin('enterprise as et', 'fs.enterprise_id',  'et.id')
                    ->where('fs.id', $id)
                    ->where('fs.user_id', Auth::user()->id)
                    ->where('fs.status', 1)
                    ->select(
                    'et.enterprise_name as enterpriseName',  
                    'fst.farm_type as farmType',
                    'fsc.farm_cat as categoryName',
                    'fss.farm_subcat as subCategoryName',
                    'fs.*'
                    )  
                    ->get(); 
      }
      else
      {
       
        $farmstore = FarmStore::from('farm_store as fs')
                    ->join('farm_store_category as fsc', 'fs.category_id',  'fsc.id') 
                    ->join('farm_store_type as fst', 'fsc.type',  'fst.id')
                    ->where('fs.id', $id)
                    ->where('fs.user_id', Auth::user()->id)
                    ->where('fs.status', 1)
                    ->select(
                    'fst.farm_type as farmType',
                    'fsc.farm_cat as categoryName',
                    'fs.*'
                    )  
                    ->get(); 
      }
      // $transaction = Transaction::where('id', $id)->first();
       return response()->json(['response' => ['status' => true, 'data' => $farmstore]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => 'Something went wrong!']], JsonResponse::HTTP_BAD_REQUEST);
    }  
  }


  public function update(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'date' => 'required',
      'name' => 'required',
      'price' => 'required',
      'source' => 'required'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response()->json(['error' => $errors->toJson()]);
    }

    try 
    {
      $obj = FarmStore::where('id',$request->id)->first();
      $type =  FarmStoreType::where('id',$obj->type)->first();
          
        $obj->date = $request->date;
        $obj->name = $request->name;
        $obj->price = $request->price;
        $obj->source = $request->source;
        if($type->id == 5)
        {
          $obj->size = $request->size;
        }  
        
        if($type->id == 6 || $type->id == 2)
        {
          $obj->description = $request->description;
          $obj->quantity = $request->quantity;
        } 

        if($type->id == 2)
        {
          $obj->expiry_date = $request->expiry_date;
        }  

        if($type->id != 5 && $type->id != 2)
        {
          $obj->condition = $request->condition;
        }  
        $obj->updatedby = Auth::user()->id;
        $obj->updated_at = Carbon::now();
        $obj->save();

      return response()->json(['response' => ['status' => true, 'message' => 'Record Updated successfully']], 
        JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      // return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    
  }

  public function detail($id)
  {
    try
    {
      $farmstore = FarmStore::from('farm_store as fs')
                    ->join('farm_store_category as fsc', 'fs.category_id',  'fsc.id') 
                    ->join('farm_store_type as fst', 'fsc.type',  'fst.id')
                    ->leftjoin('farm_store_subcategory as fss', 'fs.subcategory_id',  'fss.id')
                    ->leftjoin('enterprise as et', 'fs.enterprise_id',  'et.id')
                    ->where('fs.id', $id)
                    ->where('fs.user_id', Auth::user()->id)
                    ->where('fs.status', 1)
                    ->select(
                    'et.enterprise_name as enterpriseName',  
                    'fst.farm_type as farmType',
                    'fsc.farm_cat as categoryName',
                    'fss.farm_subcat as subCategoryName',
                    'fs.*'
                    )  
                    ->get();  
      // $transaction = Transaction::where('id', $id)->first();
       return response()->json(['response' => ['status' => true, 'data' => $farmstore]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => 'Something went wrong!']], JsonResponse::HTTP_BAD_REQUEST);
    }  
  }

  public function add_quantity(Request $request)
  {
              
    try
    {
      $update = FarmStore::where('user_id',$request->user_id)
              ->where('type', $request->type_id)
              ->where('category_id', $request->category_id)
              ->where('subcategory_id', $request->subcategory_id)
              ->where('id', $request->farm_store_id)
              ->first();

    
        
        $update->quantity = $update->quantity + $request->quantity;  
        $update->updatedby = Auth::user()->id;
        $update->updated_at = Carbon::now();
        $update->save();
        
          $history = new FarmStoreHistory;
          $history->farm_store_id = $request->farm_store_id;
          $history->date = $request->date;
          $history->name = $request->name;
          $history->quantity = $request->quantity;
          $history->price = $request->price;
          $history->value = $request->value;
          $history->forage_name = $request->forage_name;
          $history->created_by = Auth::user()->id;
          $history->created_at = Carbon::now();
          $history->save();
         
     
      
       return response()->json(['response' => ['status' => true, 'message' => 'Quantity Added successfully!']], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }  
  }

  public function remove_quantity(Request $request)
  {
              
    try
    {
      $update = FarmStore::where('user_id',$request->user_id)
              ->where('type', $request->type_id)
              ->where('category_id', $request->category_id)
              ->where('subcategory_id', $request->subcategory_id)
              ->where('id', $request->farm_store_id)
              ->first();
    
        
        $update->quantity = $update->quantity - $request->quantity;  
        $update->updatedby = Auth::user()->id;
        $update->updated_at = Carbon::now();
        $update->save();
        
          $history = new FarmStoreHistory;
          $history->farm_store_id = $request->farm_store_id;
          $history->date = $request->date;
          $history->name = $request->name;
          $history->quantity = $request->quantity;
          $history->price = $request->price;
          $history->value = $request->value;
          $history->purpose = $request->purpose;
          $history->created_by = Auth::user()->id;
          $history->created_at = Carbon::now();
          $history->save();
         
     
      
       return response()->json(['response' => ['status' => true, 'message' => 'Quantity Remove successfully!']], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }  
  }

   public function farmstore_history(Request $request)
  {
    try
    {
      $history=[];
      $details = FarmStore::from('farm_store as fs')
                    //->join('farm_store_history as fsh', 'fs.id',  'fsh.farm_store_id')
                    ->leftjoin('farm_store_type as fst', 'fs.type',  'fst.id')
                    ->leftjoin('farm_store_category as fsc', 'fs.category_id',  'fsc.id')
                    ->leftjoin('farm_store_subcategory as fssc', 'fs.subcategory_id',  'fssc.id')
                    ->where('fs.type', $request->type_id)
                    ->where('fs.category_id', $request->category_id)
                    ->where('fs.subcategory_id', $request->subcategory_id)
                    ->where('fs.user_id', $request->user_id)
                    ->where('fs.status', 1)
                    ->select(
                    'fst.farm_type as farmtype',
                    'fsc.farm_cat as categoryName',  
                    'fssc.farm_subcat as subcategoryName',
                    'fs.*'
                    )  
                    ->get();
                  foreach ($details as $key => $value) 
                  {
                    $hist = FarmStoreHistory::where('farm_store_id',$value->id)->get(); 
                    array_push($history, $hist);
                  }              
                    
      

      $data = [
              'details' => $details,
              'history' => $history
            ];

      return response()->json(['response' => ['status' => true, 'data' => $data]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }  
  }

  public function delete(Request $request)
  {
    try
    {
        $status = FarmStore::find($request->id); 
        $status->purpose = $request->purpose;  
        $status->status = 0;   
        $status->updatedby = Auth::user()->id;
        $status->updated_at = Carbon::now();
        $status->save();
      
       return response()->json(['response' => ['status' => true, 'message' => 'Record Deleted!']], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }  
  }

  public function form_store_quantity(Request $request)
  {
    try
    {
      $data = FarmStore::from('farm_store as fs')
                    ->where('fs.type', $request->type_id)
                    ->where('fs.category_id', $request->category_id)
                    ->where('fs.subcategory_id', $request->subcategory_id)
                    ->where('fs.user_id', $request->user_id)
                    ->where('fs.status', 1)
                    ->select(DB::raw('SUM(fs.quantity) as qunatity'))
                    ->get();
      return response()->json(['response' => ['status' => true, 'data' => $data]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }  
  }

  
}

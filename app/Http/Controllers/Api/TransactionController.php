<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionSubCategory;
use App\Models\TransactionCategory;
use App\Models\TransactionType;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\Enterprise;
use App\Models\User;
use Validator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class TransactionController extends Controller
{

  public function transaction_type()
  {
    try 
    {
      $t_type =  TransactionType::get();
      return response()->json(['response' => ['status' => true, 'data' => $t_type]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => 'Something went wrong!']], JsonResponse::HTTP_BAD_REQUEST);
    }  

  }

  public function transaction_category($type)
  {
    try 
    {
      $t_cat =  TransactionCategory::where('type',$type)->get();
      return response()->json(['response' => ['status' => true, 'data' => $t_cat]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => 'Something went wrong!']], JsonResponse::HTTP_BAD_REQUEST);
    }  

  }

  public function payment()
  {
    try 
    {
      $payment =  PaymentMethod::get();
      return response()->json(['response' => ['status' => true, 'data' => $payment]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => 'Something went wrong!']], JsonResponse::HTTP_BAD_REQUEST);
    }  

  }

  public function create(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'transaction_date' => 'required',
      'category_id' => 'required',
      'transaction_name' => 'required',
      'item' => 'required',
      'quantity' => 'required',
      'unit_price' => 'required',
      'amount' => 'required',
      'payment_method' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response()->json(['error' => $errors->toJson()]);
    }

    try 
    {
      $t_cat =  TransactionCategory::where('id',$request->category_id)->first();

      $photo = '';

        if ($request->hasfile('photo')) {
           $image = $request->photo;
           $extension = $image->getClientOriginalExtension();
           $destinationPath = base_path() . '/public/transaction/';
           $fileName = $request->user()->id . time() . rand() . $request->user()->id . '.' . $extension;
           $image->move($destinationPath, $fileName);


           $photo = '/transaction/' . $fileName;
        }

      Transaction::insert([
        
        'transaction_date' => $request->transaction_date,
        'category_id' => $request->category_id,
        'transaction_name' => $request->transaction_name,
        'item' => $request->item,
        'quantity' => $request->quantity,
        'unit_price' => $request->unit_price,
        'amount' => $request->amount,
        'payment_method' => $request->payment_method,
        'type' => $t_cat->type,
        'photo' => $photo,
        'user_id' => Auth::user()->id,
        'createdby' => Auth::user()->id,
        'created_at' => Carbon::now(),

      ]);
      return response()->json(['response' => ['status' => true, 'message' => 'Record Added successfully']], 
        JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => 'Record Not Added.Something went wrong!']], JsonResponse::HTTP_BAD_REQUEST);
      // return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    

  }

  public function index()
  {
    try
    {
      $transaction = Transaction::where('user_id', Auth::user()->id)->get();
      return response()->json(['response' => ['status' => true, 'data' => $transaction]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => 'Something went wrong!']], JsonResponse::HTTP_BAD_REQUEST);
    }  
  }

  
}
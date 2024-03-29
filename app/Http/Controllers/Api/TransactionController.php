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
use DB;
use PDF;
use File;

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
        'enterprise_id' => $request->enterprise_id,
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
      $transaction = Transaction::from('transaction as t')
                    ->join('transaction_category as tc', 't.category_id',  'tc.id') 
                    ->join('transaction_type as tt', 't.type',  'tt.id')
                    ->join('payment as pay', 't.payment_method',  'pay.id')
                    ->where('t.user_id', Auth::user()->id)
                    ->select(
                    'tc.transaction_cat as categoryName',
                    'tt.transaction_type as transactionType',
                    'pay.method as paymentMethod',
                    't.*'
                    )  
                    ->get();
      return response()->json(['response' => ['status' => true, 'data' => $transaction]], JsonResponse::HTTP_OK);
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
      $transaction = Transaction::from('transaction as t')
                    ->join('transaction_category as tc', 't.category_id',  'tc.id') 
                    ->join('transaction_type as tt', 't.type',  'tt.id')
                    ->join('payment as pay', 't.payment_method',  'pay.id')
                    ->where('t.id', $id)
                    ->where('t.user_id', Auth::user()->id)
                    ->select(
                    'tc.transaction_cat as categoryName',
                    'tt.transaction_type as transactionType',
                    'pay.method as paymentMethod',
                    't.*'
                    )  
                    ->first();
      // $transaction = Transaction::where('id', $id)->first();
      $t_cat =  TransactionCategory::where('type',$transaction->type)->get();
      $payment =  PaymentMethod::get();
      $data = [
               'transaction' => $transaction,
               't_cat' => $t_cat,
               'payment' => $payment,
            ];
      return response()->json(['response' => ['status' => true, 'data' => $data]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => 'Something went wrong!']], JsonResponse::HTTP_BAD_REQUEST);
    }  
  }


  public function update(Request $request)
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

     $photo = '';

        if ($request->hasfile('photo')) {
           $image = $request->photo;
           $extension = $image->getClientOriginalExtension();
           $destinationPath = base_path() . '/public/transaction/';
           $fileName = $request->user()->id . time() . rand() . $request->user()->id . '.' . $extension;
           $image->move($destinationPath, $fileName);


           $photo = '/transaction/' . $fileName;
        }

      Transaction::where('id',$request->transaction_id)
      ->update([
        'transaction_date' => $request->transaction_date,
        'category_id' => $request->category_id,
        'transaction_name' => $request->transaction_name,
        'item' => $request->item,
        'quantity' => $request->quantity,
        'unit_price' => $request->unit_price,
        'amount' => $request->amount,
        'payment_method' => $request->payment_method,
        'photo' => $photo,
        'updatedby' => Auth::user()->id,
        'updated_at' => Carbon::now(),
      ]);
      return response()->json(['response' => ['status' => true, 'message' => 'Record Updated successfully']], 
        JsonResponse::HTTP_OK);
    }  
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      // return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    

  }


  public function download_pdf($id)
  {
    try
    {
      $transaction = Transaction::from('transaction as t')
                    ->join('transaction_category as tc', 't.category_id',  'tc.id') 
                    ->join('transaction_type as tt', 't.type',  'tt.id')
                    ->join('payment as pay', 't.payment_method',  'pay.id')
                    ->where('t.id', $id)
                    ->where('t.user_id', Auth::user()->id)
                    ->select(
                    'tc.transaction_cat as categoryName',
                    'tt.transaction_type as transactionType',
                    'pay.method as paymentMethod',
                    't.*'
                    )  
                    ->first();
      // $transaction = Transaction::where('id', $id)->first();
      return response()->json(['response' => ['status' => true, 'data' => $transaction]], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }  
  }


  public function income_statement(Request $request)
  {
    try 
    {
      $user = User::where('id',$request->user_id)->first();
      if(isset($user) && $user != null)
      {

        $current_year = Carbon::today()->format('Y');
      $current_start_date = Carbon::parse('first day of January '. $current_year)->startOfDay();
      $current_end_date = Carbon::parse('last day of December '. $current_year)->endOfDay();

      $product_sale = Transaction::where('user_id',$request->user_id)->where('type',1)->whereBetween('category_id', [1, 4])
                        ->select(DB::raw('SUM(unit_price) as product_sale'))->first();
      //dd($product_sale->product_sale);
      $costs_goods_sold = Transaction::where('user_id',$request->user_id)->whereBetween('type', [2, 3])
                        ->select(DB::raw('SUM(unit_price) as costs_goods_sold'))->first(); 

      $gross_profit_income = floatval(floatval($product_sale->product_sale) - floatval($costs_goods_sold->costs_goods_sold)); 
      //dd($gross_profit_income);
      
      $operating_expenses = Transaction::where('user_id',$request->user_id)->where('type', 4)->where('category_id', '!=', 40)
                        ->select(DB::raw('SUM(unit_price) as operating_expenses'))->first();

      $operating_profit_income = floatval(floatval($gross_profit_income) - floatval($operating_expenses->operating_expenses));
      //dd($operating_profit_income);
      $non_operating_income = Transaction::where('user_id',$request->user_id)->where('type',1)->whereBetween('category_id', [5, 8])
                        ->select(DB::raw('SUM(unit_price) as non_operating_income'))->first();

      $non_operating_expenses = Transaction::where('user_id',$request->user_id)->where('type',4)->where('category_id', 40)
                        ->select(DB::raw('SUM(unit_price) as non_operating_expenses'))->first(); 
      $net_profit_income_before_taxes = 
      floatval(floatval($operating_profit_income) + floatval($non_operating_income->non_operating_income) - floatval($non_operating_expenses->non_operating_expenses)); 
      $income_tax = 0; 
      $net_profit_income = floatval(floatval($net_profit_income_before_taxes) - floatval($income_tax));              

     
      $pdf = PDF::loadView('reports.income_statement', compact('product_sale','costs_goods_sold','operating_expenses','non_operating_income','non_operating_expenses','user',
        'gross_profit_income','operating_profit_income','net_profit_income_before_taxes','income_tax','net_profit_income'));

      return $pdf->setPaper('A4')->download('INCOME STATEMENT.pdf');
         
     /* return View('reports.income_statement', compact('product_sale','costs_goods_sold','operating_expenses','non_operating_income','non_operating_expenses',
        'gross_profit_income','operating_profit_income','net_profit_income_before_taxes','income_tax','net_profit_income'));    */  

      }
      else{
          echo "User Not Found";
        }  
      
      
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }  

  }

  public function balance_sheet(Request $request)
  {
    try 
    {
      $user = User::where('id',$request->user_id)->first();
      if(isset($user) && $user != null)
      {
          $current_assets = Transaction::where('user_id',$request->user_id)->where('type',1)->whereBetween('category_id', [1, 4])
                                  ->select(DB::raw('SUM(unit_price) as current_assets'))->first();
          //dd($regular_income_sales->regular_income_sales);

          $asset_purchases = Transaction::where('user_id',$request->user_id)->where('type',6)->whereBetween('category_id', [48, 52])
                             ->where('payment_method', '!=', 5)->select(DB::raw('SUM(unit_price) as asset_purchases'))->first();
          //dd($asset_purchases->asset_purchases);  
                      
          $asset_sales = Transaction::where('user_id',$request->user_id)->where('type',7)->whereBetween('category_id', [53, 57])
                        ->select(DB::raw('SUM(unit_price) as asset_sales'))->first(); 
          //dd($asset_sales->asset_sales);
          $long_term_assets  =  floatval(floatval($asset_purchases->asset_purchases) + floatval($asset_sales->asset_sales)); 

          $total_assets =  floatval(floatval($current_assets->current_assets) + floatval($long_term_assets));

          $farm_input_service = Transaction::where('user_id',$request->user_id)->whereBetween('type',[2,3])->whereBetween('category_id', [9, 31])
                        ->select(DB::raw('SUM(unit_price) as farm_input_service'))->first(); 
          
          $monthly_payment = Transaction::where('user_id',$request->user_id)->where('type',4)->where('category_id', '!=', 40)
                        ->select(DB::raw('SUM(unit_price) as monthly_payment'))->first();

          $current_liabilities = floatval(floatval($farm_input_service->farm_input_service) + floatval($monthly_payment->monthly_payment));

          $long_term_liabilities = Transaction::where('user_id',$request->user_id)->where('type',6)->whereBetween('category_id', [48, 52])
                             ->where('payment_method',5)->select(DB::raw('SUM(unit_price) as long_term_liabilities'))->first();

          $total_liabilities =  floatval(floatval($current_liabilities) + floatval($long_term_liabilities->long_term_liabilities));    
          
          $equity  =  floatval(floatval($total_assets) - floatval($total_liabilities));                               

          //dd($total_assets);  
                /*$data = [
                   'total_assets' => $total_assets,
                   'total_liabilities' => $total_liabilities,
                   'equity' => $equity,
                ];*/
          $pdf = PDF::loadView('reports.balance_sheet', compact('current_assets','asset_purchases','asset_sales','long_term_assets','total_assets','user',
           'farm_input_service','monthly_payment','current_liabilities','long_term_liabilities','total_liabilities','equity'));

          return $pdf->setPaper('A4')->download('BALANCE SHEET.pdf');
             
          /*return View('reports.balance_sheet', compact('current_assets','asset_purchases','asset_sales','long_term_assets','total_assets',
           'farm_input_service','monthly_payment','current_liabilities','long_term_liabilities','total_liabilities','equity')); */ 
      }
      else{
          echo "User Not Found";
        }                
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }  

  }

  public function enterprise_report(Request $request)
  {
    try 
    {
      $user = User::where('id',$request->user_id)->first();
      if(isset($user) && $user != null)
      {

          $sql_direct_income = 'select tc.transaction_cat, SUM(unit_price) as income from `transaction` as t 
            left join `transaction_category` as tc on t.category_id = tc.id where t.user_id = '.$request->user_id.' and t.enterprise_id = '.$request->enterprise_id.' and t.type = 1 and t.category_id between 5 and 8 GROUP by tc.transaction_cat';
          $d_income = DB::select($sql_direct_income);

          $direct_income = Transaction::where('user_id',$request->user_id)->where('enterprise_id',$request->enterprise_id)
                                      ->where('type',1)->whereBetween('category_id', [5, 8])->select(DB::raw('SUM(unit_price) as direct_income'))->first();

          $sql_direct_expenses = 'select SUM(unit_price) as price,tc.transaction_cat as direct_expenses from `transaction` as t 
                                  left join `transaction_category` as tc on t.category_id = tc.id where t.user_id = '.$request->user_id.' 
                                  and t.enterprise_id = '.$request->enterprise_id.' and t.type between 2 and 3 GROUP by tc.transaction_cat'; 
          $d_expense = DB::select($sql_direct_expenses);                                                   

          $direct_expenses = Transaction::where('user_id',$request->user_id)->where('enterprise_id',$request->enterprise_id)->whereBetween('type', [2, 3])
                            ->select(DB::raw('SUM(unit_price) as direct_expenses'))->first();
        
          $entreprise_profit_loss  =  floatval(floatval($direct_income->direct_income) - floatval($direct_expenses->direct_expenses)); 

          //dd($entreprise_profit_loss);
          
          $pdf = PDF::loadView('reports.enterprise_report', compact('direct_income','d_income','direct_expenses','d_expense','entreprise_profit_loss','user'));

          return $pdf->setPaper('A4')->download('Enterprise.pdf');
             
          //return View('reports.enterprise_report', compact('direct_income','d_income','direct_expenses','d_expense','entreprise_profit_loss')); 
      }
      else{
          echo "User Not Found";
        }               
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }  

  }

  public function inactive_enterpise(Request $request)
  {
    try
    {
      $enterprise  = Enterprise::where('user_id', $request->user_id)->whereNotIn('id', [$request->enterprise_id])->delete();
      

      return response()->json(['response' => ['status' => true, 'message' => 'Record deleted successfully']], JsonResponse::HTTP_OK);
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }    
  }

  public function cash_flow(Request $request)
  {
    try 
    {
      $user = User::where('id',$request->user_id)->first();
      if(isset($user) && $user != null)
      {
          $current_year = Carbon::today()->format('Y');
          $current_start_date = Carbon::parse('first day of January '. $current_year)->startOfDay();
          $current_end_date = Carbon::parse('last day of December '. $current_year)->endOfDay();

          $cash_received_operation = Transaction::where('user_id',$request->user_id)->where('type',1)->whereBetween('category_id', [1, 8])
                            ->select(DB::raw('SUM(unit_price) as product_sale'))->first();

          $cash_paidout_operation = Transaction::where('user_id',$request->user_id)->whereBetween('type', [2, 3,4])->whereNotIn('category_id', [41])
                            ->select(DB::raw('SUM(unit_price) as product_sale'))->first();                  

          $operating_activities = floatval(floatval($cash_received_operation->product_sale) - floatval($cash_paidout_operation->product_sale)); 

          //dd($operating_activities);

          
          $cash_received_financing = Transaction::where('user_id',$request->user_id)->where('type',5)->whereBetween('category_id', [44, 47])
                            ->select(DB::raw('SUM(unit_price) as product_sale'))->first();

          $cash_paidout_financing = Transaction::where('user_id',$request->user_id)->where('type',4)->where('category_id', 41)
                            ->select(DB::raw('SUM(unit_price) as product_sale'))->first();                  

          $financing_activities = floatval(floatval($cash_received_financing->product_sale) - floatval($cash_paidout_financing->product_sale)); 


          $cash_received_investment = Transaction::where('user_id',$request->user_id)->where('type',6)->whereNotIn('category_id', [49,50])
                              ->select(DB::raw('SUM(unit_price) as product_sale'))->first();
          //dd($cash_received_investment->product_sale);                    
                            
          $cash_paidout_investment = Transaction::where('user_id',$request->user_id)->where('type',7)->whereNotIn('category_id', [55])
                            ->select(DB::raw('SUM(unit_price) as product_sale'))->first();                  

          $investment_activities = floatval(floatval($cash_received_investment->product_sale) - floatval($cash_paidout_investment->product_sale)); 
          //dd($investment_activities);

          $net_cash_flow = floatval(floatval($operating_activities) - floatval($investment_activities));            

         
          $pdf = PDF::loadView('reports.cash_flow', compact('cash_received_operation','cash_paidout_operation','operating_activities','cash_received_financing','user',
                      'cash_paidout_financing','financing_activities','cash_received_investment','cash_paidout_investment','investment_activities','net_cash_flow'));

          return $pdf->setPaper('A4')->download('Cash Flow Statement.pdf');
             
          /*return View('reports.cash_flow', compact('cash_received_operation','cash_paidout_operation','operating_activities','cash_received_financing','user',
                      'cash_paidout_financing','financing_activities','cash_received_investment','cash_paidout_investment','investment_activities','net_cash_flow'));*/ 
      }
      else{
          echo "User Not Found";
        }                      
      
    } 
    catch (Exception $e) 
    {
      return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
    }  

  }  

  
}

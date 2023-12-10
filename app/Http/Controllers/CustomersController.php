<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Customer;

class CustomersController extends Controller
{
       //show all customers
       public function index()
       {
           return view('customers' , [
               'customers' => Customer::orderBy('created_at','desc')->paginate(Customer::$paginate)
           ]);
       }
   
       //get more customers
       public function getMore(Request $request) 
       {
           if ( $request->post() )
           {
               return view('layouts.divs.customer' , [
                   'customers' => Customer::orderBy('created_at','desc')->paginate(Customer::$paginate)
               ]);
           }
       }
   
       //add new customer
       public function add(Request $request)
       {
           if ( $request->post() )
           {
               Customer::validation($request);
   
               $customer = Customer::create([
                   'name' => $request->name,
                   'phone_number' => $request->phone_number,
                   'car_number' => $request->car_number,
                   'address' => $request->address
               ]);
   
               return view( 'layouts.divs.customer' , [ 'customers' => [$customer] ] );
           }
       }
   
       //get customer based on place id
       public function get(Request $request)
       {
           if ($request->ajax())
           {
               return Customer::find($request->id);
           }
       }
   
       //update customer
       public function update(Request $request, $id)
       {
           if ($request->ajax())
           {
               Customer::validation($request);
   
               $customer = Customer::find($id);
   
               $customer->update([
                   'name' => $request->name,
                   'phone_number' => $request->phone_number,
                   'car_number' => ( $request->car_number ) ? $request->car_number : "",
                   'address' => ( $request->address ) ? $request->address : ""
               ]);
               
               return view( 'layouts.divs.customer' , [ 'customers' => [$customer] ] );
           }
       }
   
       //delete customer
       public function delete(Request $request,$id)
       {
           if ($request->ajax())
           {
               $customer = Customer::find( $id );
               $customer->delete();
           }
       }
   
       //filter customer
       public function filter(Request $request)
       {
           if ( $request->ajax() )
           {
               $text = $request->text;

                if ( $text )
                    $customers = Customer::where('name','LIKE','%'.$text.'%')
                        ->orWhere('phone_number','LIKE','%'.$text.'%')
                        ->orWhere('car_number','LIKE','%'.$text.'%')
                        ->orWhere('address','LIKE','%'.$text.'%')
                        ->get();
                else
                    return view('layouts.divs.customer' , [
                        'customers' => Customer::orderBy('created_at','desc')->paginate(Customer::$paginate)
                    ]);
            
               return view( 'layouts.divs.customer' , [ 'customers' => $customers ] );
           }
       }
}

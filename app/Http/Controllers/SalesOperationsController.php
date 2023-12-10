<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

use \App\SaleOperation;
use \App\Part;
use Carbon\Carbon;
use \App\Customer;

class SalesOperationsController extends Controller
{

    public static $last_get_operation; // last results got

    public function index()
    {
        $username = "";
        if ( auth()->user()->type != "admin" ) // if user not admin => show just his operations
            $username = auth()->user()->name;
        return view("sales_operations", [
            "operations" => SaleOperation::where('username','like','%' . $username . '%')
            ->orderBy('created_at','desc')
            ->paginate(SaleOperation::$paginate)
        ]);
    }

    //add
    public function add(Request $request)
    {
        if ( $request->ajax() )
        {
            //validations :
            SaleOperation::validation( $request );

            if ( ! $request->parts_id[0] || count( $request->parts_id ) < 1 )
                throw ValidationException::withMessages(['parts_id' => 'يجب تحديد القطعة']);

            $combine_data = $this->combineData( $request );

            $data = json_encode( $combine_data['data'] , true );

            $date = null;
            if ( $request->type_paid == 0 )
                $date = date("Y-m-d H:i:s");

            SaleOperation::create([
                "user_id" => auth()->user()->id,
                "customer_id" => $combine_data["customer_id"],
                "username" => auth()->user()->name,
                "data" => $data,
                "note" => $request->note,
                "price" => $combine_data["total_price"],
                "discount_add" => $request->add_discount,
                "paid_at" => $date
            ]);
        }
    }

    public function combineData($request)
    {
        $data;
        $parts_id = $request->parts_id;
        $quantities = $request->quantities;
        $adds_discounts = $request->adds_discounts;
        $j;
        $total_price = 0;

        $customer_id;

        if ( $request->customer_name ) // check if new customer
        {
            $cu = Customer::where('name' , '=' , $request->customer_name)->first();

            if ( $cu != null)
            {
                $customer_id = $cu->id;
            }
            else
            {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'phone_number' => "",
                    'car_number' => "",
                    'address' => ""
                ]);
                $customer_id = $customer->id;
            }
        }

        for ($j = 0; $j < count( $parts_id ); $j++) {
            // save all data in array
            $temp = array(
                "part_id" => Part::find($parts_id[$j])->id,
                "part_name" => Part::find($parts_id[$j])->name,
                "quantity" => $quantities[$j],
                "add_sub" => $adds_discounts[$j],
                "total_price" => (  Part::find($parts_id[$j])->selling_price ) * $quantities[$j],
                "number" => Part::find($parts_id[$j])->number,
                "original_number" => Part::find($parts_id[$j])->original_number,
                "description" => Part::find($parts_id[$j])->description,
                "orignal_price" => Part::find($parts_id[$j])->orignal_price
            );

            $data[ $j ] = $temp;

            // get total price
            $total_price += ( ( Part::find($parts_id[$j])->selling_price ) * ( $quantities[$j] ) );

            //decrease all quantities in this operation
            $part = Part::find( $parts_id[$j] );
            $part->count = $part->count - $quantities[$j];
            $part->save();
        }

        return array(
            "data" => $data,
            "customer_id" => $customer_id,
            "total_price" => $total_price
        );
    }

    //edit
    public function edit(Request $request, $id)
    {
        if ($request->ajax())
        {
            return view('layouts.divs.edit_sale_operation',[
                'operation' => SaleOperation::find($id)
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $this->restore( $id );

        $combine_data = $this->combineData( $request );

        $data = json_encode( $combine_data['data'] , true );

        $operation = SaleOperation::find( $id );

        $operation->user_id = auth()->user()->id;

        $operation->customer_id = $combine_data["customer_id"];

        $operation->username = auth()->user()->name;

        $operation->data = $data;

        $operation->note = $request->note;

        $operation->price = $combine_data["total_price"];

        $operation->discount_add = $request->add_discount;

        if ( $operation->paid_at == null && $request->type_paid == 0  )
            $operation->paid_at = date("Y-m-d h:i:s");

        if ( $operation->paid_at != null && $request->type_paid == 1  )
            $operation->paid_at = null;


        $operation->save();

        return view( 'layouts.divs.sale_operation' , [ 'operations' => [$operation] , 'update' => 1 ] );
    }

    //delete
    public function delete(Request $request,$id)
    {
        if ($request->ajax())
        {
            $this->restore($id);
            $operation = SaleOperation::find( $id );
            $operation->delete();
        }
    }

    //return quantity part for operation
    public function restore($id)
    {
        $operation = SaleOperation::find( $id );

        //return all parts in this operation
        $data = json_decode( $operation->data , true );

        for ($i = 0; $i < sizeof($data); $i++)
        {
            $part = Part::find( $data[$i]["part_id"] );
            $part->count += $data[$i]["quantity"];
            $part->save();
        }
    }

    //get more parts
    public function getMore(Request $request)
    {
        if ( $request->post() )
        {
            $username = "";
            if ( auth()->user()->type != "admin" ) // if user not admin => show just his operations
                $username = auth()->user()->name;
            return view('layouts.divs.sale_operation' , [
                'operations' => SaleOperation::where('username','like','%' . $username . '%')
                ->orderBy('created_at','desc')
                ->paginate(Part::$paginate)
            ]);
        }
    }

    //filter sales ops
    public function filter(Request $request)
    {
        if ( $request->ajax() )
        {
            $text = $request->text;

            $username = "";
            if ( auth()->user()->type != "admin" ) // if user not admin => show just his operations
                $username = auth()->user()->name;

            $start = $request->start;
            $end = $request->end;
            $end = date('Y-m-d H:i:s', strtotime($end . ' +1 day'));

            $operations;

            if ( $start && $end )
            { //if enter date show [ all ] ( no paginate ) data between these dates
                $operations = SaleOperation::leftJoin('customers','customers.id','=','sales_operations.customer_id')
                    ->where(function (Builder $query) use ($start,$end,$username) {
                        return $query
                                    ->where('sales_operations.created_at','>=',$start)
                                    ->where('sales_operations.created_at','<=',$end)
                                    ->where('username','like','%' . $username . '%');
                    })
                    ->where(function (Builder $query) use($request) {
                        if ($request->not_paid_check == 'true')
                            return $query->whereNull('paid_at');
                    })
                    ->where(function (Builder $query) use ($text) {
                        return $query
                                    ->where('sales_operations.note','like','%'.$text.'%')
                                    ->orWhere('sales_operations.paid_at','like','%'.$text.'%')
                                    ->orWhere('sales_operations.data','like','%'.$text.'%')
                                    ->orWhere('username','like','%'.$text.'%')
                                    ->orWhere('customers.name','like','%'.$text.'%')
                                    ->orWhere('customers.car_number','like','%'.$text.'%');
                    })
                    ->orderBy('created_at','desc')
                    ->get('sales_operations.*');
            }
            else
            { // if enter text show from 0 to paginate (50)
                if ( $text )
                $operations = SaleOperation::leftJoin('customers','customers.id','=','sales_operations.customer_id')
                            ->where(function (Builder $query) use($username) {
                                return $query->where('username','like','%' . $username . '%');
                            })
                            ->where(function (Builder $query) use($request) {
                                if ($request->not_paid_check == 'true')
                                    return $query->whereNull('paid_at');
                            })
                            ->where(function (Builder $query) use($text){
                                return $query
                                    ->where('sales_operations.note','like','%'.$text.'%')
                                    ->orWhere('sales_operations.data','like','%'.$text.'%')
                                    ->orWhere('username','like','%'.$text.'%')
                                    ->orWhere('customers.name','like','%'.$text.'%')
                                    ->orWhere('customers.car_number','like','%'.$text.'%');
                            })
                            ->orderBy('created_at','desc')
                            ->skip(0)
                            ->take( SaleOperation::$paginate )
                            ->get('sales_operations.*');
                else // text is null => show first paginate
                    $operations = SaleOperation::where('username','like','%' . $username . '%')
                    ->where(function (Builder $query) use($request) {
                        if ($request->not_paid_check == 'true')
                            return $query->whereNull('paid_at');
                    })
                    ->orderBy('created_at','desc')->paginate(SaleOperation::$paginate);
            }

            return view( 'layouts.divs.sale_operation' , [ 'operations' => $operations ] );
        }
    }

    //filter betwen two dates
    public function filterDate(Request $request)
    {
        if ( $request->ajax() )
        {
            $start = $request->start;
            $end = $request->end;

            $end = date('Y-m-d H:i:s', strtotime($end . ' +1 day'));

            $operations = SaleOperation::where('created_at','>=',$start)
            ->where('created_at','<=',$end)
            ->orderBy('created_at','desc')
            ->get();

            return view( 'layouts.divs.sale_operation' , [ 'operations' => $operations ] );
        }
    }

    //calculate amount between two dates
    public function calculateAmount(Request $request)
    {
        if ( $request->ajax() )
        {
            $amount = SaleOperation::where('created_at','>=',$start)
            ->where('created_at','<=',$end)
            ->sum('price');
            return $amount;
        }
    }

    //set operation as paid
    public function setPaid(Request $request)
    {
        if ( $request->ajax() )
        {
            $s = SaleOperation::find( $request->id );
            $s->paid_at = date("Y-m-d h:i:s");
            $s->save();
        }
    }


    public function statistics(Request $request)
    {
        if ($request->ajax())
        {
            $t1_from = $request->t1_f_from_date;
            $t1_to = date('Y-m-d H:i:s', strtotime($request->t1_t_from_date . ' +1 day'));

            $t2_from = $request->t2_f_from_date;
            $t2_to = date('Y-m-d H:i:s', strtotime($request->t2_t_from_date . ' +1 day'));

            $t1 = SaleOperation::where('created_at','>=',$t1_from)
            ->where('created_at','<=',$t1_to)->get();

            $t2 = SaleOperation::where('created_at','>=',$t2_from)
            ->where('created_at','<=',$t2_to)->get();

            $final_amount = 0;
            foreach ( $t1 as $t )
            {
                $data = json_decode( $t->data , true  );

                for ($i = 0; $i < sizeof( $data ); $i++)
                {
                    $final_amount += ( $data[$i]["total_price"] - ($data[$i]["orignal_price"] * $data[$i]["quantity"]) );
                    $final_amount += ( isset($data[$i]["add_sub"]) ? $data[$i]["add_sub"] : 0 );
                }
                $final_amount += ( $t->discount_add );
            }
            $t1_amount = $final_amount;

            $final_amount = 0;
            foreach ( $t2 as $t )
            {
                $data = json_decode( $t->data , true  );

                for ($i = 0; $i < sizeof( $data ); $i++)
                {
                    $final_amount += ( $data[$i]["total_price"] - ($data[$i]["orignal_price"] * $data[$i]["quantity"]) );
                    $final_amount += ( isset($data[$i]["add_sub"]) ? $data[$i]["add_sub"] : 0 );
                }
                $final_amount += ( $t->discount_add );
            }
            $t2_amount = $final_amount;


            return array(
                't1_amount' => $t1_amount,
                't2_amount' => $t2_amount
            );

        }
    }

}

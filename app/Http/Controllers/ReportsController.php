<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\SaleOperation;
use \App\SupplyOperation;
use \App\Customer; 
use \App\Part;

class ReportsController extends Controller
{
    //type [ 0 => sales operations , 1 => one sale operation ] || withNote[ 0 => with, 1=> without ] 
    public function sales_operations(Request $request , $type , $sale_operation_id)
    {
        $sale_operations;
        if ( $type == 0 )
        {
            $sale_operation_id = explode(",",$sale_operation_id);
            $sale_operations = SaleOperation::orderBy('created_at','desc')->find( $sale_operation_id );
        }
        else
            $sale_operations = [SaleOperation::find( $sale_operation_id )];


        $columns = [
            "اسم القطعة",
            "الكمية" ,
            "السعر",
         ];


        return view('reports.sales_operations', [
            'columns' => $columns,
            'operations' => $sale_operations
        ]);
        
    }

    public function sales_operations_customer(Request $request , $customer_id)
    {
        $customer = Customer::find( $customer_id );

        $start = $_GET['start_date'];
        $end = date('Y-m-d H:i:s', strtotime($_GET['end_date'] . ' +1 day'));        

   
        $sale_operations = SaleOperation::where('customer_id',$customer_id)
                                        ->where('created_at','>=',$start)
                                        ->where('created_at','<=',$end)
                                        ->get();

        $columns = [
            "اسم القطعة",
            "الكمية" ,
            "السعر",
         ];


        return view('reports.sales_operations_customer', [
            'columns' => $columns,
            'operations' => $sale_operations,
            'customer' => $customer
        ]);
        
    }

    public function supply_operations(Request $request, $supplier_id)
    {

        $end = date('Y-m-d H:i:s', strtotime($_GET['end_date'] . ' +1 day'));        
       
        $supply_operations = SupplyOperation::where('supplier_id','=',$supplier_id )
            ->where('created_at','>=',$_GET['start_date'])
            ->where('created_at','<=', $end)
            ->get();

        if ( $supply_operations->count() == 0)
        {
            return
            '
                <center>
                    <h1> لا يوجد اي نتيجة في التاريخ المدخل </h1>
                    <a href="#" style="font-size: 25px;" onclick="history.back()"> خلف </a>  
                </center>
            ';
        }

        $columns = [
            "اسم القطعة",
            "الكمية",
            "السعر",
            "التاريخ و الوقت",
         ];

        $j = 0;
        $rows;
        $total_price = 0;
        foreach ($supply_operations as $operation)
        {
            $temp = array(
                $operation->part_name,
                $operation->count,
                sprintf('%.2f',$operation->price) . " شيكل",
                date('Y-m-d h:i A', strtotime($operation->created_at)),
            );
            $total_price += $operation->price;
            $rows[$j] = $temp;
            $j++;
        }

        return view('reports.suppliers_operations', [
            'columns' => $columns,
            'rows'=> $rows,
            'supply_operations' => $supply_operations,
            'total_price' => $total_price
        ]);
    }   

    public function parts(Request $request)
    {
        $parts = Part::all();

        $columns = [
            "الرقم الاصلي",
            "الرقم",
            "البيان",
            "الكمية",
            "الرف",
            "المورد",
            "السعر"
            ];


        $j = 0;
        $rows;
        $total_price = 0;
        foreach ($parts as $part) {
            $temp = array(
                $part->original_number,
                $part->number,
                $part->description,
                $part->count,
                $part->place->name,
                $part->supplier->name,
                ( $part->selling_price ) . " شيكل",
            );
            $total_price += $part->selling_price * $part->count;
            $rows[$j] = $temp;
            $j++;
        }


        return view('reports.parts', [
            'columns' => $columns,
            'rows'=> $rows,
            'total_price' => $total_price
        ]); 
    }

    public function empty_parts(Request $request)
    {
        $parts = Part::where('count','<=','0')->get();

        $columns = [
            "الرقم الاصلي",
            "الرقم",
            "البيان",
            "المورد",
            ];


        $j = 0;
        $rows;
        $total_price = 0;
        foreach ($parts as $part) {
            $temp = array(
                $part->original_number,
                $part->number,
                $part->description,
                $part->supplier->name,
            );
            $rows[$j] = $temp;
            $j++;
        }


        return view('reports.parts', [
            'columns' => $columns,
            'rows'=> $rows,
            'total_price' => $total_price
        ]); 
    }

}

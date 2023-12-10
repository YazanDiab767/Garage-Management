<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Supplier;
use \App\SupplyOperation;
use Illuminate\Database\Eloquent\Builder;


class SuppliersController extends Controller
{

    //show all suppliers
    public function index()
    {
        return view('suppliers' , [
            'suppliers' => Supplier::paginate(Supplier::$paginate)
        ]);
    }

    //get more suppliers
    public function getMore(Request $request) 
    {
        if ( $request->post() )
        {
            return view('layouts.divs.supplier' , [
                'suppliers' => Supplier::paginate(Supplier::$paginate)
            ]);
        }
    }

    //add new supplier
    public function add(Request $request)
    {
        if ( $request->post() )
        {
            Supplier::validation($request);

            $supplier = Supplier::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => ( $request->address ) ? $request->address : ""
            ]);

            return view( 'layouts.divs.supplier' , [ 'suppliers' => [$supplier] ] );
        }
    }

    //get supplier based on supplier id
    public function get(Request $request)
    {
        if ($request->ajax())
        {
            return Supplier::find($request->id);
        }
    }

    //get history supplier based on supplier id
    public function getHistory(Request $request)
    {
        if ($request->ajax())
        {
            $s = SupplyOperation::where('supplier_id',$request->id)
                ->orderBy('created_at', 'desc')
                ->paginate(SupplyOperation::$paginate);
                
            return view('layouts.divs.supply_operation',[
                'supply_operations' => $s,
                'id' => $request->id
            ]);     
        }
    }

    //get more history supplier
    public function getMoreHistory(Request $request)
    {
        if ($request->ajax())
        {
            $s = SupplyOperation::orderBy('created_at', 'desc')
            ->paginate(SupplyOperation::$paginate)
            ->where('supplier_id',$request->id);  

            return view('layouts.divs.supply_operation',[
                'supply_operations' => $s,
                'id' => $request->id
            ]);     
        }
    }

    //update supplier
    public function update(Request $request, $id)
    {
        if ($request->ajax())
        {
            Supplier::validation($request);

            $supplier = Supplier::find($id);

            $supplier->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => ( $request->address ) ? $request->address : ""
            ]);
            
            return view( 'layouts.divs.supplier' , [ 'suppliers' => [$supplier] ] );
        }
    }

    //delete supplier
    public function delete(Request $request,$id)
    {
        if ($request->ajax())
        {
            $supplier = Supplier::find( $id );
            $supplier->delete();
        }
    }

    //filter supllier
    public function filter(Request $request)
    {
        if ( $request->ajax() )
        {
            $text = $request->text;
            if ($text)
                $suppliers = Supplier::where('name','LIKE','%'.$text.'%')
                    ->orWhere('phone_number','LIKE','%'.$text.'%')
                    ->orWhere('address','LIKE','%'.$text.'%')
                    ->get();
            else
                return view('layouts.divs.supplier' , [
                    'suppliers' => Supplier::paginate(Supplier::$paginate)
                ]);

            return view( 'layouts.divs.supplier' , [ 'suppliers' => $suppliers ] );
        }
    }

    //add new supply operation
    public function addSupplyOperation(Request $request)
    {
        if ($request->ajax())
        {
            $supplier = Supplier::find($request->supplier_id);
            
            $paid_at = null;
            
            if ( $request->paid == 1 )
                $paid_at = date("Y-m-d h-i:s"); 

            $so = SupplyOperation::create([
                'part_name' => $request->part_name,
                'part_id' => '0',
                'supplier_id' => $supplier->id,
                'count' => $request->count,
                'price' => $request->price,
                'paid_at' => $paid_at
            ]);

            return view('layouts.divs.supply_operation',[
                'supply_operations' => [$so],
                'id' => $request->supplier_id
            ]);
        }
    }

    //update supply operation
    public function updateSupplyOperation(Request $request, $id)
    {
        if ( $request->ajax() )
        {
            $operation = SupplyOperation::find( $id );

            $operation->part_name = $request->part_name;

            $operation->count = $request->count;

            $operation->price = $request->price;
            
            if ( !$operation->paid_at && $request->paid == 1 )
                $operation->paid_at = date("Y-m-d h:i:s");

            if ( $operation->paid_at && $request->paid == 0 )
                $operation->paid_at = null;

            $operation->save();

            return view('layouts.divs.supply_operation',[
                'supply_operations' => [$operation],
                'id' => $operation->supplier_id, 
            ]);
        }
    }

    //delete supply operation

    public function deleteSupplyOperation(Request $request, $id)
    {
        if ($request->ajax())
        {
            $s = SupplyOperation::find( $id );
            $s->delete();   
        }
    }

    //save notes
    public function saveNotes(Request $request, $id)
    {
        if ($request->ajax())
        {
            $supplier = Supplier::find($id);
            $supplier->notes = $request->notes;
            $supplier->save();
        }
    }

    //filter supllier operations
    public function filterOperations(Request $request, $id)
    {
        if ( $request->ajax() )
        {
            $text = $request->text;
            $operations;
            if ($text)
            {
                $operations = SupplyOperation::where(function (Builder $query) use ($text, $id) {
                    return $query->where('supplier_id', $id);
                })
                ->where(function(Builder $query) use ($text) {
                    return $query->where('part_name','like','%'.$text.'%')
                                ->orWhere('created_at','like','%'.$text.'%');
                })
                ->orderBy('created_at','desc')
                ->skip(0)
                ->take( SupplyOperation::$paginate )
                ->get();
            }
            else
            {
                $operations = SupplyOperation::where('supplier_id',$id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(SupplyOperation::$paginate);           
            }

            return view('layouts.divs.supply_operation',[
                'supply_operations' => $operations,
                'id' => $id
            ]);
        }
    }

}

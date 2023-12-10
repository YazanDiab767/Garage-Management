<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \App\Part;
use \App\Supplier;
use \App\Place;
use \App\SupplyOperation;

class PartsController extends Controller
{
    //show all parts
    public function index()
    {
        return view('parts' , [
            'parts' => Part::orderBy('created_at','desc')->paginate(Part::$paginate)
        ]);
    }

    //add new part
    public function add(Request $request)
    {
        if ( $request->post() )
        {
            Part::validation($request);

            $image = "parts/part.png";
            if ( $request->hasFile('image') )
            {
                $image = $request->image->store('parts','public');
            }

            $part = Part::create([
                'qr_code' => '',
                'original_number' => $request->original_number,
                'number' => $request->number,
                'description' => $request->description,
                'name' => $request->name,
                'count' => $request->count,
                'supplier_id' => $request->supplier,
                'image' => $request->image,
                'place_id' => $request->place,
                'orignal_price' => $request->orignal_price,
                'selling_price' => $request->selling_price,
                'paid' => ( $request->paid ) ? 1 : 0,
                'image' => $image
            ]);

            //to log operations
            // SupplyOperation::create([
            //     'part_name' => $part->name,
            //     'supplier_name' => $part->supplier->name,
            //     'part_id' => $part->id,
            //     'supplier_id' => $part->supplier->id,
            //     'count' => $part->count,
            //     'price' => ( $part->count * $part->orignal_price ),
            //     'paid' => false
            // ]);

            return view( 'layouts.divs.part' , [ 'parts' => [$part] ] );
        }
    }

    //get part based on part id
    public function get(Request $request)
    {
        if ($request->ajax())
        {
            return Part::find($request->id);
        }
    }

    //update part
    public function update(Request $request, $id)
    {
        if ($request->ajax())
        {
            Part::validation($request);

            $part = Part::find($id);

            $image = $part->image;

            if ( $request->hasFile('image') )
            {
                //delete old image
    
                if ( $part->image != "parts/part.png" )
                {
                    Storage::disk('public')->delete($part->image);  
                }
                $image = $request->image->store('parts','public');
            }

            //get new count ( quantity )
            // $new_count = $request->count - $part->count;
            // if ( $new_count < 0 )
            //     $new_count *= -1;

            //to log operations
            // SupplyOperation::create([
            //     'part_name' => $part->name,
            //     'supplier_name' => $part->supplier->name,
            //     'part_id' => $part->id,
            //     'supplier_id' => $part->supplier->id,
            //     'count' => $new_count,
            //     'price' => ( $new_count * $request->orignal_price ),
            //     'paid' => false
            // ]);

            $part->update([
                'qr_code' => '',
                'original_number' => $request->original_number,
                'number' => $request->number,
                'description' => $request->description,
                'name' => $request->name,
                'count' => $request->count,
                'supplier_id' => $request->supplier,
                'image' => $request->image,
                'place_id' => $request->place,
                'orignal_price' => $request->orignal_price,
                'selling_price' => $request->selling_price,
                'paid' => ( $request->paid ) ? 1 : 0,
                'image' => $image
            ]);

            return view( 'layouts.divs.part' , [ 'parts' => [$part] ] );
        }
    }

    //add quantity
    public function addQuantity(Request $request, $id){
        if ( $request->ajax() )
        {
            $part = Part::find( $id );
            $count = $part->count;
            $part->count += $request->count;
            $part->save();

            //to log operations
            // SupplyOperation::create([
            //     'part_name' => $part->name,
            //     'supplier_name' => $part->supplier->name,
            //     'part_id' => $part->id,
            //     'supplier_id' => $part->supplier->id,
            //     'count' => $request->count,
            //     'price' => ( $part->count * $part->orignal_price ),
            //     'paid' => false
            // ]);
            
            return view( 'layouts.divs.part' , [ 'parts' => [$part] ] );
        }
    }

    //get more parts
    public function getMore(Request $request) 
    {
        if ( $request->post() )
        {
            return view('layouts.divs.part' , [
                'parts' => Part::orderBy('created_at','desc')->paginate(Part::$paginate)
            ]);
        }
    }

    //delete part
    public function delete(Request $request,$id)
    {
        if ($request->ajax())
        {
            $part = Part::find( $id );
            $part->delete();
        }
    }

    //filter part
    public function filter(Request $request)
    {
        if ( $request->ajax() )
        {
            $text = $request->text;            

            if ( $text )
                $parts = Part::join('suppliers','suppliers.id','=','parts.supplier_id')
                    ->leftJoin('places','places.id','=','parts.place_id')
                    ->where('parts.number','like','%'.$text.'%')
                    ->orWhere('parts.original_number','like','%'.$text.'%')
                    ->orWhere('parts.description','like','%'.$text.'%')
                    ->orWhere('parts.name','LIKE','%' .$text. '%')
                    ->orWhere('parts.count','LIKE','%' .$text. '%')
                    ->orWhere('parts.orignal_price','LIKE','%'.$text.'%')
                    ->orWhere('parts.selling_price','LIKE','%'.$text.'%')
                    ->orWhere('suppliers.name','LIKE','%'.$text.'%')
                    ->orWhere('places.name','LIKE','%'.$text.'%')
                    ->skip(0)
                    ->take( Part::$paginate )
                    ->get('parts.*');
            else
                $parts = Part::orderBy('created_at','desc')->paginate(Part::$paginate);
        
            return view( 'layouts.divs.part' , [ 'parts' => $parts ] );
        }
    }

    //set part as paid
    public function setPaid(Request $request)
    {
        if ( $request->ajax() )
        {
            $s = SupplyOperation::find( $request->id );
            $s->paid_at = date("Y-m-d h:i:s");
            $s->save();
        }
    }
}

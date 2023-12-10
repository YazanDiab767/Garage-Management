<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Place;

class PlacesController extends Controller
{
    //show all places
    public function index()
    {
        return view('places' , [
            'places' => Place::orderBy('created_at','desc')->get()
        ]);
    }

    //add new place
    public function add(Request $request)
    {
        if ( $request->post() )
        {
            Place::validation($request);

            $place = Place::create([
                'name' => $request->name,
                'place' => $request->place 
            ]);

            return view( 'layouts.divs.place' , [ 'places' => [$place] ] );
        }
    }

    //get place based on place id
    public function get(Request $request)
    {
        if ($request->ajax())
        {
            return Place::find($request->id);
        }
    }

    //update place
    public function update(Request $request, $id)
    {
        if ($request->ajax())
        {
            Place::validation($request);

            $place = Place::find($id);

            $place->update([
                'name' => $request->name,
                'place' => $request->place
            ]);
            
            return view( 'layouts.divs.place' , [ 'places' => [$place] ] );
        }
    }

    //delete place
    public function delete(Request $request,$id)
    {
        if ($request->ajax())
        {
            $place = Place::find( $id );
            $place->delete();
        }
    }
}

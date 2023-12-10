<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Place extends Model
{
    protected $fillable = [ 'name' , 'place' ];

    //validation
    public static function validation(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1',
        ],[
            'name.required' => "يجب ادخال اسم الرف",
            'name.min' => "يجب ان يكون اسم الرف على الاقل حرف واحد"
        ]);
    }

    // relationships 
    public function parts()
    {
        return $this->hasMany('App\Part');
    }
}

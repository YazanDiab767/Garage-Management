<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Supplier extends Model
{
    protected $fillable = [ 'name' , 'phone_number' , 'address' ];

    protected $attributes = [
        'address' => ''
    ];

    public static $paginate = 50;

    //validation
    public static function validation(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
        ],[
            'name.required' => "يجب ادخال اسم المورد",
            'phone_number.required' => "يجب ادخال رقم الهاتف"
        ]);
    }

    // relationships
    public function parts()
    {
        return $this->hasMany('App\Part');
    }
}

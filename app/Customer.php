<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Customer extends Model
{
    protected $fillable = [ 'name' , 'phone_number' , 'car_number' , 'address' ];

    protected $attributes = [
        'car_number' => ''
    ];

    public static $paginate = 50;

    //validation
    public static function validation(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ],[
            'name.required' => "يجب ادخال اسم الزبون",
        ]);
    }

    //realtionships

    public function debts()
    {
        return $this->hasMany('App\Debt');
    }

    public function sales()
    {
        return $this->hasMany('App\SaleOperation');
    }
}

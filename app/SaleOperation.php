<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class SaleOperation extends Model
{
    protected $table = "sales_operations";

    protected $fillable = [ "user_id" , "username" ,"customer_id" , "data" , "note" , "paid" , "price", "discount_add" , "paid_at"];

    public static $paginate = 50;

    //validation
    public static function validation(Request $request)
    {
        $request->validate([
            'customer_name' => 'required'
        ],[
            'customer_name.required' => "يجب تحديد الزبون",
        ]);
    }

        
    // realtionships

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function part()
    {
        return $this->belongsTo('App\Part');
    }

}

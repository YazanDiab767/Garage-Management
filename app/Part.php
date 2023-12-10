<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Part extends Model
{
    protected $fillable = [ 'original_number', 'description' ,'number' , 'name' , 'count' , 'supplier_id' , 'image' , 'place_id' , 'orignal_price' , 'selling_price' ];

    protected $attributes = [
        'qr_code' => '',
        'image' => ''
    ];

    public static $paginate = 50;

    //validation
    public static function validation(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'count' => 'required',
            'supplier' => 'required',
            'place' => 'required',
            'orignal_price' => 'required',
            'selling_price' => 'required',
            'image' => 'image'
        ],[
            'name.required' => "يجب ادخال اسم القطعة",
            'count.required' => "يجب ادخال كمية القطعة",
            'supplier.required' => " يجب تحديد المورد ",
            'place.required' => " يجب تحديد الرف",
            'orignal_price.required' => " يجب ادخال السعر الاصلي ",
            'selling_price.required' => " يجب ادخال سعر البيع ",
            'image.image' => 'يجب اختيار صورة صحيحة'
        ]);
    }

    // relationships
    
    public function place()
    {
        return $this->belongsTo('App\Place');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }
}

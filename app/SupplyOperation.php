<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplyOperation extends Model
{
    protected $table = "supply_operations";

    public static $paginate = 50;

    protected $fillable = [ 'supplier_id' , 'part_name'  , 'count' , 'price' , 'paid_at' ];


    // realtionships
    public function part()
    {
        return $this->belongsTo('App\Part');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }
}

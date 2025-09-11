<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentDetails extends Model
{
    protected $fillable = [
        'equipment_category_id',
        'loc_name',
        'details_aname',
        'details_lname',
        'details_qty',
        'date_from',
        'date_to',
        'note',
    ];

    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class, 'equipment_category_id', 'id');
    }
}

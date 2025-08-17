<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'aname',
        'lname',
        'note',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function equipmentDetails()
    {
        return $this->hasMany(EquipmentDetails::class);
    }
}

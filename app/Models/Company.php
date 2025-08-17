<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'aname',
        'lname',
    ];

    public function equipmentCategories()
    {
        return $this->hasMany(EquipmentCategory::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wharehouse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type_warehouse',
        'village_id',
        'status',
        'created_by',
    ];

    /**
     * Get the village associated with the warehouse.
     */
    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }

    /**
     * Get the user who created the warehouse.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
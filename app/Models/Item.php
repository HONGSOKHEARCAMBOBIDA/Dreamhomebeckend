<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'item_type_id',
        'measurement_id',
        'value_measurement',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the item type associated with the item.
     */
    public function itemType()
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    /**
     * Get the measurement associated with the item.
     */
    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'measurement_id');
    }

    /**
     * Get the user who created the item.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the item.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
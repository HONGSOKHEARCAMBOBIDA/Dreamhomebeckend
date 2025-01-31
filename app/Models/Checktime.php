<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckTime extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wh_id',
        'check_date',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the warehouse associated with the check time.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'wh_id');
    }

    /**
     * Get the user who created the check time.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the check time.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'district_id',
    ];

    /**
     * Get the district associated with the commune.
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
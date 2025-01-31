<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'communce_id',
    ];

    /**
     * Get the commune associated with the village.
     */
    public function commune()
    {
        return $this->belongsTo(Commune::class, 'communce_id');
    }
}
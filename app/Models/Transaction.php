<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wh_id',
        'item_id',
        'qty_in',
        'qty_out',
        'type_transaction',
        'user_id',
        'description'
    ];

    /**
     * Get the warehouse associated with the transaction.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'wh_id');
    }

    /**
     * Get the item associated with the transaction.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * Get the user associated with the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
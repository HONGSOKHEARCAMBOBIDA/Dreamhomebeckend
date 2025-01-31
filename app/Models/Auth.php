<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $table = 'auth_logs'; // Example table name
    protected $fillable = [
        'user_id',
        'action', // e.g., 'login', 'logout'
        'ip_address',
        'user_agent',
    ];

    // Define relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
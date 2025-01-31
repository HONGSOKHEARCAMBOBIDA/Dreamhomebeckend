<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rolehaspermission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_has_permissions';

    /**
     * The primary key for the model.
     *
     * @var array<string>
     */
    protected $primaryKey = ['role_id', 'permission_id'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    /**
     * Get the role associated with the role-permission relationship.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get the permission associated with the role-permission relationship.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission; 
class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'department_id',
    ];

    /**
     * Get the department associated with the role.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }
}
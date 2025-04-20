<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    /**
     * The table associated with the model.
     * Laravel will use "roles" by default, so this property is optional
     * unless your table name differs.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        // Add other role columns here if you have, e.g. 'display_name'
    ];

    /**
     * Indicates if the model should be timestamped.
     * Set to false if your "roles" table does not use created_at/updated_at.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The users that belong to this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        // By default, Laravel will assume:
        //   pivot table: role_user
        //   foreign keys: role_id, user_id
        return $this->belongsToMany(
            User::class,   // Related model
            'role_user',   // Pivot table name
            'role_id',     // Foreign key on pivot for this model
            'user_id'      // Foreign key on pivot for the related model
        );
    }
}

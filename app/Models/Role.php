<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    CONST ROLE_POST_AUTHOR = 'post_author';
    CONST ROLE_GUEST_USER = 'guest_user';
    CONST ROLE_ADMIN = 'admin';

    protected $table = 'roles';

     /************************************************************************************
     * RELATIONS
     */

    /**
     * Get related users
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_role');
    }

}

<?php

namespace App\Models;

use App\Jobs\StoreSocialLogJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use function Illuminate\Events\queueable;

class Social extends Model
{
    use HasFactory, SoftDeletes;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'socials';
/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'text',
        'sector_id',
        'author_id',
        'tag_id',
        'is_approved_by_post_author',
        'is_approved_by_guest_user',

    ];

    protected $casts = [
        'is_approved_by_post_author' => 'boolean',
        'is_approved_by_guest_user' => 'boolean',

    ];
    public static function booted()
    {
        static::created(queueable(function ($social) {
            StoreSocialLogJob::dispatchAfterResponse($social->author_id, $social->id, SocialLog::ACTION_CREATE);
        }));

        static::updated(queueable(function ($social) {
            StoreSocialLogJob::dispatchAfterResponse(Auth::id(), $social->id, SocialLog::ACTION_UPDATE);
        }));

        static::deleted(queueable(function ($social) {
            StoreSocialLogJob::dispatchAfterResponse(Auth::id(), $social->id, SocialLog::ACTION_DESTROY);
        }));
    }
    /************************************************************************************
     * RELATIONS
     */

    /**
     * Get the author
     *
     * @return HasOne
     */
    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id');
    }

    /**
     * Get the sector
     *
     * @return HasOne
     */
    public function sector(): HasOne
    {
        return $this->hasOne(Sector::class, 'id');
    }

    /**
     * Get logs
     *
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(SocialLog::class,'social_id');
    }
    /**
     * Get tags
     *
     * @return
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Tag', 'post_tag','tag_id', 'post_id');
    }
    /**
     * Scopes
     *
     *
     */

     /**
     * Filters projects by user role
     *
     * @param $query
     * @param $role
     * @return mixed
     */


     public function scopeByUserRole($query, $role)
    {
        switch ($role) {
            case Role::ROLE_POST_AUTHOR:
                return $query->where('is_approved_by_guest_user', true)
                    ->where('is_approved_by_post_author', false);
            case Role::ROLE_GUEST_USER:
                return $query->where('is_approved_by_guest_user', false)
                    ->where('is_approved_by_post_author', true);


            default:
                return $query->where('is_approved_by_post_author', false)
                    ->where('is_approved_by_guest_user', false);

        }
    }

    public function scopeHaveErrors($query)
    {
        $query->where(function ($q) {
            $q->where('is_approved_by_post_author', true)->where(function ($sub) {
                $sub->where('is_approved_by_guest_user', false);

            })->orWhere('is_approved_by_guest_user', true)->where(function ($sub) {
                $sub->where('is_approved_by_post_author', false);

            });
        });
    }

    /************************************************************************************
     * FUNCTIONS
     */

    /**
     * Check if user can update flags
     *
     * @param $role
     * @return bool
     */


    public function userRoleCanUpdateFlags($role): bool
    {
        switch ($role) {
            case Role::ROLE_POST_AUTHOR:
                return !$this->is_approved_by_guest_user;
            case Role::ROLE_GUEST_USER:
                return $this->is_approved_by_post_author;

            default:
                return false;
        }
    }

    /**
     * Count errors for
     *
     * @return int
     */


    public function countErrors(): int
    {
        $errors = 0;
        $roles = [Role::ROLE_POST_AUTHOR, Role::ROLE_GUEST_USER];

        foreach ($roles as $role) {
            if (!$this->userRoleCanUpdateFlags($role)) {
                $errors++;
            }
        }

        return $errors;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialLog extends Model
{
    use HasFactory;

    const ACTION_CREATE='CREATE';
    const ACTION_UPDATE='UPDATE';
    const ACTION_DESTROY='DESTROY';

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'social_logs';

    /************************************************************************************
     * RELATIONS
     */

    /**
     * Get the author
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class,'author_id');
    }
}

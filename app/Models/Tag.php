<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tag extends Model
{
    use HasFactory, BelongsToMany;
    protected $table = 'tag';

    protected $fillable = [
        'tag_name',
        'slug',
    ];
    public function socials():BelongsToMany

    {
        return $this->belongsToMany('App\Models\Tag', 'post_tag','tag_id', 'post_id');
      }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Article extends Model
{

    use HasFactory;
    protected $fillable = ['title', 'content', 'url', 'source', 'published_at'];


    public function setPublishedAtAttribute($value)
    {

        $this->attributes['published_at'] = $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }
}

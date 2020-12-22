<?php

namespace Lyncas\Models;

class Book extends Model
{
    protected $fillable = [
        'title',
        'authors',
        'published_at',
        'description',
        'image_url'
    ];
}

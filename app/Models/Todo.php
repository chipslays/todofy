<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_FINISH = 2;
    const STATUS_ARCHIVE = 3;
    const STATUS_DELETE = 4;

    const SHARE_PRIVATE = 1;
    const SHARE_LINK = 2;
    const SHARE_USERNAME = 3;

    const COLLAPSED = 1;
    const PINNED = 1;

    const RESET = 0;

    protected $table = 'todo';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'data',
        'status',
        'shared',
        'code',
        'views',
        'pinned',
        'collapsed',
        'category_id',
        'author_id',
    ];
}

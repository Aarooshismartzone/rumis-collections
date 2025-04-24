<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generic extends Model
{
    use HasFactory;

    protected $table = 'generics';

    protected $fillable = ['key', 'value'];

    /**
     * Retrieve a generic value by key.
     */
    public static function getValue($key, $default = null)
    {
        return self::where('key', $key)->value('value') ?? $default;
    }
}

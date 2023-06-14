<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graphic extends Model
{
    use HasFactory;

    public const SPROTT = 1;
    public const LORENZ = 2;
    public const CHEN = 3;
    public const ROSSLER = 4;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'parameters',
        'results',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'parameters' => 'array',
        'results' => 'array',
        'type' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSprottGraphics()
    {
        return $this->where('type',self::SPROTT)->get();
    }
}

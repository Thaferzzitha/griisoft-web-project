<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graphic extends Model
{
    use HasFactory;

    public const SPROTT = 'sprott';
    public const LORENZ = 'lorenz';
    public const CHEN = 'chen';
    public const ROSSLER = 'rossler';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'parameters',
        'results',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'results',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'parameters' => 'array',
        'results' => 'array',
        'type' => 'string',
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

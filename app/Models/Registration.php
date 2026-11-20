<?php

namespace App\Models;

use Database\Factories\RegistrationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['full_name', 'email', 'password', 'date_of_birth'])]
#[Hidden(['password'])]
class Registration extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_VERIFIED = 'verified';

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    /** @use HasFactory<RegistrationFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

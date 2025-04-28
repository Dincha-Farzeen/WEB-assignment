<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    // Specify the table name (if it doesn't follow Laravel's naming convention)
    protected $table = 'reviews';

    // Specify the primary key column (if it's not `id`)
    protected $primaryKey = 'review_id';

    // Disable timestamps if your table doesn't have `created_at` and `updated_at`
    public $timestamps = false;

    // Define fillable fields
    protected $fillable = [
        'rating',
        'comment',
        'date',
        'u_id',
    ];

    // Relationship: A review belongs to a registered user
    public function user(): BelongsTo
    {
        return $this->belongsTo(RegisteredUser::class, 'u_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    // Specify the table name (if it doesn't follow Laravel's naming convention)
    protected $table = 'bookings';

    // Specify the primary key column (if it's not `id`)
    protected $primaryKey = 'booking_id';

    // Disable timestamps if your table doesn't have `created_at` and `updated_at`
    public $timestamps = false;

    // Define fillable fields
    protected $fillable = [
        'date',
        'status',
    ];

    // Relationship: A booking belongs to many photographers
    public function photographers(): BelongsToMany
    {
        return $this->belongsToMany(Photographer::class, 'booked_photographers');
    }
}
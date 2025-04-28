<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Photographer extends Model
{
    // Specify the table name (if it doesn't follow Laravel's naming convention)
    protected $table = 'photographers';

    // Specify the primary key column (if it's not `id`)
    protected $primaryKey = 'photographer_id';

    // Disable timestamps if your table doesn't have `created_at` and `updated_at`
    public $timestamps = false;

    // Define fillable fields
    protected $fillable = [
        'name',
        'specialization',
    ];

    // Relationship: A photographer belongs to many bookings
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booked_photographers');
    }
}
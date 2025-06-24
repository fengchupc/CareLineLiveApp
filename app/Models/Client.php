<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'care_needs'
    ];
    /**
     * Get the shifts for the client.
     */
    public function shifts() {
        return $this->hasMany(Shift::class);
    }
}

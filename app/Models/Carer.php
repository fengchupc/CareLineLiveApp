<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carer extends Model {
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'qualifications'
    ];

    /**
     * Get the shifts for the carer.
     */
    public function shifts() {
        return $this->hasMany(Shift::class);
    }
}

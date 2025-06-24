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
    

    /**
     * Check if the carer has an overlapping shift.
     */
    /*public function hasOverlappingShift($startTime, $endTime, $excludeShiftId = null) {
        $query = $this->shifts()
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeShiftId) {
            $query->where('id', '!=', $excludeShiftId);
        }

        return $query->exists();
    }*/
}

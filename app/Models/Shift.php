<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model {
    use HasFactory;

    protected $fillable = [
        'carer_id',
        'client_id',
        'start_time',
        'end_time',
        'notes'
    ];
    
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];
    /**
     * Get the carer that owns the shift.
     */
    public function carer() {
        return $this->belongsTo(Carer::class);
    }
    /**
     * Get the client that owns the shift.
     */
    public function client() {
        return $this->belongsTo(Client::class);
    }

    /**
     * Check if the shift overlaps with another shift.
     */
    public function overlapsWith(Shift $otherShift) {
        return $this->start_time < $otherShift->end_time && 
               $this->end_time > $otherShift->start_time;
    }
}

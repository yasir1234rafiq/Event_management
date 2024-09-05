<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description','date','place','image','event_type','status'
    ];
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function scopeFilter($query, $fromDate = null, $toDate = null, $place = null)
    {
        if ($fromDate && $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
        }

        if ($place) {
            $query->where('place', 'like', '%' . $place . '%');
        }

        return $query;
    }

}

<?php

namespace App\Models;

use App\Observers\bookingObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Observers\bokingObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([bookingObserver::class])]

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['event_id', 'user_id', 'status'];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Scope to search by event title
    public function scopeWhereEventTitle(Builder $query, $title)
    {
        return $query->whereHas('event', function (Builder $query) use ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        });
    }

    // Scope to search by user name
    public function scopeWhereUserName(Builder $query, $name)
    {
        return $query->whereHas('user', function (Builder $query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        });
    }

    // Scope to search by event date
    public function scopeWhereEventDate(Builder $query, $date)
    {
        return $query->whereHas('event', function (Builder $query) use ($date) {
            $query->whereDate('date', '=', $date);
        });
    }
}

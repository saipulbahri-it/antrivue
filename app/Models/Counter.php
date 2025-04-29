<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    /** @use HasFactory<\Database\Factories\CounterFactory> */
    use HasFactory;

    protected $fillable = ['name', 'service_id', 'ip_address', 'user_id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    // function last QueueCalled
    public function lastQueueCalled()
    {
        return $this->hasOne(Queue::class)->where('status', 'calling')->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isAvailable(): bool
    {
        return $this->lastQueueCalled === null;
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    /** @use HasFactory<\Database\Factories\CounterFactory> */
    use HasFactory;

    protected $fillable = ['name', 'service_id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}

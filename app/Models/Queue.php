<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    /** @use HasFactory<\Database\Factories\QueueFactory> */
    use HasFactory;

    protected $fillable = ['number', 'service_id', 'counter_id', 'status', 'called_at'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
}

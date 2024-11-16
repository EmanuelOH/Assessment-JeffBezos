<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'reason',
        'status',
        'nota',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

}

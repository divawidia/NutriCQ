<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'nama_user',
        'nama_dokter',
        'tanggal',
        'deskripsi',
        'start_time',
        'end_time',
        'meeting_link',
        'lama_meeting',
        'file_resep',
        'status',
        'user_id',
        'user_dokter_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

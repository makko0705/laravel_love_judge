<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'partner_name', 'diagnosis_content', 'compatibility',
        'diagnosis_date', 'go_or_wait'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

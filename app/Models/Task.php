<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'due_date',
        'is_cancelled',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_cancelled' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
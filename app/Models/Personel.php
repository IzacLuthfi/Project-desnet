<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personel extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'nama',
        'role',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

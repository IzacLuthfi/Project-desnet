<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'jenis_dokumen',
        'file_path',
        'keterangan',
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

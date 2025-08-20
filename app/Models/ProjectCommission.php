<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCommission extends Model
{
    protected $table = 'project_commissions'; // pastikan sesuai tabel

    protected $fillable = [
        'project_id',
        'project_personel_id',
        'user_id',
        'margin',
        'persentase',
        'nilai_komisi'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function projectPersonel()
    {
        return $this->belongsTo(ProjectPersonel::class, 'project_personel_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

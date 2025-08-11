<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
  use HasFactory;

  protected $fillable = ['judul', 'nilai', 'pm_id', 'status', 'status_dokumen', 'status_komisi'];

  public function projectPersonel()
  {
    return $this->hasMany(ProjectPersonel::class);
  }
  public function documents()
  {
    return $this->hasMany(ProjectDocument::class);
  }
  public function projectDocuments()
  {
    return $this->hasMany(\App\Models\ProjectDocument::class, 'project_id');
  }
  public function projectManager()
  {
    return $this->belongsTo(User::class, 'pm_id');
  }
  public function komisi()
  {
    return $this->hasMany(\App\Models\ProjectCommission::class, 'project_id');
  }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['judul', 'nilai', 'pm', 'status'];

  public function projectPersonel()
{
    return $this->hasMany(ProjectPersonel::class);
}



}



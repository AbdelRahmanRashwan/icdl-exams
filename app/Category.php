<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'free_tests'];

    public function skillsCards()
    {
        return $this->hasMany(SkillsCard::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}

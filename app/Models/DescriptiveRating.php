<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescriptiveRating extends Model
{
    protected $fillable = ['student_id', 'descriptive_question_id', 'year', 'bimester', 'rating'];
}

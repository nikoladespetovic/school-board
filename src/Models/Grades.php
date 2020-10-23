<?php

namespace App\Models;

use App\Logic\Model;

class Grades extends Model {
    public int $id;

    public int $student_id;

    public int $grade_value;


}
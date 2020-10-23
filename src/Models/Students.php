<?php

namespace App\Models;

use App\Logic\Model;

class Students extends Model {
    protected string $tableName = 'students';

    public int $id;

    public int $board_id;

    public string $first_name;

    public string $last_name;


}
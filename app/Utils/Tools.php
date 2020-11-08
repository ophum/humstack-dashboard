<?php

namespace App\Utils;

use App\Models\Team;
use App\Models\Problem;

class Tools
{
    public static function getDeployName(string $name, Team $team, Problem $problem)
    {
        return $team->id_prefix . '_' . $problem->name . '_' . $name;
    }
}

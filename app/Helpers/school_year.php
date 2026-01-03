<?php

use App\Models\SchoolYear;

if (! function_exists('currentSchoolYear')) {
	function currentSchoolYear()
	{
	    $year = SchoolYear::where('tenant_id', app('tenant')->id)
	        ->where('is_active', true)
	        ->first();

	    if (! $year) {
	        abort(500, "Aucune année scolaire active définie");
	    }

	    return $year;
	}

}

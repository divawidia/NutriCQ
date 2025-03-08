<?php

namespace App\Services;

class GoalService
{
    /**
     * Get the TDEE adjustment value based on gender.
     *
     * @param string $gender The gender of the user ('male' or 'female').
     * @return int The TDEE adjustment value (5 for male, -161 for female).
     */
    public function getGenderTDEE(string $gender): int
    {
        return ($gender === 'male') ? 5 : -161;
    }

    
}

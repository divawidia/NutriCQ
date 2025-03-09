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

    /**
     * Get the numeric activity level multiplier based on the tingkat_aktivitas string.
     *
     * @param string $activityLevel
     * @return float
     */
    public function getActivityLevelMultiplier(string $activityLevel): float
    {
        return match ($activityLevel) {
            'sedentary' => 1.2,
            'lightly_active' => 1.375,
            'moderately_active' => 1.550,
            'very_active' => 1.725,
            'extra_active' => 1.9,
        };
    }

    /**
     * Get the numeric activity level multiplier based on the tingkat_aktivitas string.
     *
     * @param int $weight
     * @param int $height
     * @param int $age
     * @param int $genderTDEE
     * @return float
     */
    public function getBMRValue(int $weight, int $height, int $age, int $genderTDEE): float
    {
        return (10 * $weight + 6.25 * $height - 5 * $age) + $genderTDEE;
    }

    /**
     * Calculate the Total Daily Energy Expenditure (TDEE) based on BMR and activity level.
     *
     * @param float $bmr The Basal Metabolic Rate (BMR) of the user.
     * @param float $activityLevelValue The multiplier based on the user's activity level.
     * @return float The calculated TDEE value.
     */
    public function getTDEEValue(float $bmr, float $activityLevelValue): float
    {
        return $bmr * $activityLevelValue;
    }

}

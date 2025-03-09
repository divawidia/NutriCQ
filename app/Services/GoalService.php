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


    public function getProteinNeeds(float $tdee): float
    {
        return $tdee * 30/100 / 4;
    }

    public function getCarbNeeds(float $tdee): float
    {
        return $tdee * 40/100 / 4;
    }

    public function getFatsNeeds(float $tdee): float
    {
        return $tdee * 30/100 / 9;
    }

    public function getWaterNeeds(float $weight): float
    {
        return $weight / 30;
    }

    public function getFiberNeeds(float $tdee): float
    {
        return $tdee / 1000 * 14;
    }

    /**
     * Calculate the recommended calcium intake based on the age.
     *
     * @param int $age The age of the person in years.
     * @return int The recommended daily calcium intake in milligrams.
     */
    public function getCalciumNeeds(int $age)
    {
        if ($age < 1) {
            $calcium = 270;
        } elseif ($age >= 1 && $age <= 3) {
            $calcium = 650;
        } elseif (($age >= 4 && $age <= 9) || ($age >= 19 && $age <= 49)) {
            $calcium = 1000;
        } elseif (($age >= 10 && $age <= 18) || $age >= 50) {
            $calcium = 1200;
        }

        return $calcium;
    }

    /**
     * Calculate phosphorus intake based on age.
     *
     * @param int $age The age of the individual.
     * @return int The required phosphorus intake.
     */
    public function calculatePhosphorus(int $age): int
    {
        if ($age < 1) {
            $phosphorus = 250;
        } elseif ($age >= 1 && $age <= 9) {
            $phosphorus = 500;
        } elseif ($age >= 10 && $age <= 18) {
            $phosphorus = 1200;
        } elseif ($age >= 19) {
            $phosphorus = 700;
        }
        return $phosphorus;
    }

    /**
     * Calculate iron intake based on age and gender.
     *
     * @param int $age The age of the individual.
     * @param string $gender The gender of the individual ('male' or 'female').
     * @return int The required iron intake.
     */
    public function calculateIron(int $age, string $gender): int
    {
        if ($age < 1) {
            $iron = 11;
        } elseif ($age >= 1 && $age <= 3) {
            $iron = 7;
        } elseif ($age >= 4 && $age <= 9) {
            $iron = 10;
        } elseif (($age >= 10 && $age <= 12) || ($age >= 50 && $gender == 'female')) {
            $iron = 8;
        } elseif ($age >= 19 && $gender == 'male') {
            $iron = 9;
        } elseif ($age >= 13 && $age <= 18 && $gender == 'male') {
            $iron = 11;
        } elseif ($age >= 13 && $age <= 18 && $gender == 'female') {
            $iron = 15;
        } elseif ($age >= 19 && $age <= 49 && $gender == 'female') {
            $iron = 18;
        }
        return $iron;
    }

}

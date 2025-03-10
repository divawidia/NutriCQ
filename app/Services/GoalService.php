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

    /**
     * Calculate sodium intake based on age and gender.
     *
     * @param int $age The age of the individual.
     * @param string $gender The gender of the individual ('male' or 'female').
     * @return int The required sodium intake.
     */
    public function calculateSodium(int $age, string $gender): int
    {
        if ($age < 1) {
            $sodium = 370;
        } elseif ($age >= 1 && $age <= 3) {
            $sodium = 800;
        } elseif ($age >= 4 && $age <= 6) {
            $sodium = 900;
        } elseif (($age >= 7 && $age <= 9) || ($age >= 80)) {
            $sodium = 1000;
        } elseif (($age >= 10 && $age <= 12) || ($age >= 50 && $age <= 64) && $gender == 'male') {
            $sodium = 1300;
        } elseif ($age >= 65 && $age <= 80 && $gender == 'male') {
            $sodium = 1100;
        } elseif ($age >= 65 && $age <= 80 && $gender == 'female') {
            $sodium = 1200;
        } elseif (($age >= 10 && $age <= 12) || ($age >= 50 && $age <= 64) && $gender == 'female') {
            $sodium = 1400;
        } elseif (($age >= 13 && $age <= 15) || ($age >= 19 && $age <= 49)) {
            $sodium = 1500;
        } elseif ($age >= 16 && $age <= 18 && $gender == 'female') {
            $sodium = 1600;
        } elseif ($age >= 16 && $age <= 18 && $gender == 'male') {
            $sodium = 1700;
        }
        return $sodium;
    }

    /**
     * Calculate potassium intake based on age and gender.
     *
     * @param int $age The age of the individual.
     * @param string $gender The gender of the individual ('male' or 'female').
     * @return int The required potassium intake.
     */
    public function calculatePotassium(int $age, string $gender): int
    {
        if ($age < 1) {
            $potassium = 700;
        } elseif ($age >= 1 && $age <= 3) {
            $potassium = 2600;
        } elseif ($age >= 4 && $age <= 6) {
            $potassium = 2700;
        } elseif ($age >= 7 && $age <= 9) {
            $potassium = 3200;
        } elseif ($age >= 10 && $age <= 12 && $gender == 'male') {
            $potassium = 3900;
        } elseif ($age >= 10 && $age <= 12 && $gender == 'female') {
            $potassium = 4400;
        } elseif ($age >= 13 && $age <= 15) {
            $potassium = 4800;
        } elseif ($age >= 16 && $age <= 18 && $gender == 'male') {
            $potassium = 5300;
        } elseif ($age >= 16 && $age <= 18 && $gender == 'female') {
            $potassium = 5000;
        } elseif ($age >= 19) {
            $potassium = 4700;
        }
        return $potassium;
    }

    /**
     * Calculate copper intake based on age.
     *
     * @param int $age The age of the individual.
     * @return int The required copper intake.
     */
    public function calculateCopper(int $age): int
    {
        if ($age < 1) {
            $copper = 220;
        } elseif ($age >= 1 && $age <= 3) {
            $copper = 340;
        } elseif ($age >= 4 && $age <= 6) {
            $copper = 440;
        } elseif ($age >= 7 && $age <= 9) {
            $copper = 570;
        } elseif ($age >= 10 && $age <= 12) {
            $copper = 700;
        } elseif ($age >= 13 && $age <= 15) {
            $copper = 795;
        } elseif ($age >= 16 && $age <= 18) {
            $copper = 890;
        } elseif ($age >= 19) {
            $copper = 900;
        }
        return $copper;
    }

    /**
     * Calculate zinc intake based on age and gender.
     *
     * @param int $age The age of the individual.
     * @param string $gender The gender of the individual ('male' or 'female').
     * @return int The required zinc intake.
     */
    public function calculateZinc(int $age, string $gender): int
    {
        if ($age <= 3) {
            $zinc = 3;
        } elseif ($age >= 4 && $age <= 9) {
            $zinc = 5;
        } elseif (($age >= 10 && $age <= 12) || ($age >= 19 && $gender == 'female')) {
            $zinc = 8;
        } elseif ($age >= 13 && $gender == 'male') {
            $zinc = 11;
        } elseif ($age >= 13 && $age <= 18 && $gender == 'female') {
            $zinc = 9;
        }
        return $zinc;
    }

    /**
     * Calculate retinol (Vitamin A) intake based on age and gender.
     *
     * @param int $age The age of the individual.
     * @param string $gender The gender of the individual ('male' or 'female').
     * @return int The required retinol intake.
     */
    public function calculateRetinol(int $age, string $gender): int
    {
        if ($age <= 3) {
            $retinol = 400;
        } elseif ($age >= 4 && $age <= 6) {
            $retinol = 450;
        } elseif ($age >= 7 && $age <= 9) {
            $retinol = 500;
        } elseif (($age >= 10 && $age <= 15 && $gender == 'male') || ($age >= 10 && $gender == 'female')) {
            $retinol = 600;
        } elseif ($age >= 16 && $age <= 18 && $gender == 'male') {
            $retinol = 700;
        } elseif ($age >= 19 && $gender == 'male') {
            $retinol = 650;
        }
        return $retinol;
    }

    /**
     * Calculate beta-carotene intake based on age.
     *
     * @param int $age The age of the individual.
     * @return int The required beta-carotene intake.
     */
    public function calculateBetaCarotene(int $age): int
    {
        if ($age <= 12) {
            $betaCarotene = 6;
        } elseif ($age >= 13) {
            $betaCarotene = 15;
        }
        return $betaCarotene;
    }

}

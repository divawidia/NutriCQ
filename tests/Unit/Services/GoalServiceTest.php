<?php

namespace Services;

use PHPUnit\Framework\TestCase;
use App\Services\GoalService;

class GoalServiceTest extends TestCase
{
    protected GoalService $goalService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->goalService = new GoalService();
    }

    public function test_get_gender_tdee()
    {
        $this->assertEquals(5, $this->goalService->getGenderTDEE('male'));
        $this->assertEquals(-161, $this->goalService->getGenderTDEE('female'));
    }

    public function test_get_activity_level_multiplier()
    {
        $this->assertEquals(1.2, $this->goalService->getActivityLevelMultiplier('sedentary'));
        $this->assertEquals(1.375, $this->goalService->getActivityLevelMultiplier('lightly_active'));
        $this->assertEquals(1.550, $this->goalService->getActivityLevelMultiplier('moderately_active'));
        $this->assertEquals(1.725, $this->goalService->getActivityLevelMultiplier('very_active'));
        $this->assertEquals(1.9, $this->goalService->getActivityLevelMultiplier('extra_active'));
    }

    public function test_get_bmr_value()
    {
        $this->assertEquals(1673.75, $this->goalService->getBMRValue(70, 175, 25, 5));
    }

    public function test_get_tdee_value()
    {
        $this->assertEquals(2000, $this->goalService->getTDEEValue(1600, 1.25));
    }

    public function test_get_macronutrient_needs()
    {
        $tdee = 2000;
        $this->assertEquals(150, $this->goalService->getProteinNeeds($tdee));
        $this->assertEquals(200, $this->goalService->getCarbNeeds($tdee));
        $this->assertEquals(66.67, round($this->goalService->getFatsNeeds($tdee), 2));
    }

    public function test_get_water_needs()
    {
        $this->assertEquals(2, $this->goalService->getWaterNeeds(60));
    }

    public function test_get_fiber_needs()
    {
        $this->assertEquals(28, $this->goalService->getFiberNeeds(2000));
    }

    public function test_get_calcium_needs()
    {
        $this->assertEquals(270, $this->goalService->getCalciumNeeds(0));
        $this->assertEquals(650, $this->goalService->getCalciumNeeds(2));
        $this->assertEquals(1000, $this->goalService->getCalciumNeeds(5));
        $this->assertEquals(1200, $this->goalService->getCalciumNeeds(15));
        $this->assertEquals(1000, $this->goalService->getCalciumNeeds(30));
        $this->assertEquals(1200, $this->goalService->getCalciumNeeds(60));
    }

    public function test_calculate_phosphorus()
    {
        $this->assertEquals(250, $this->goalService->calculatePhosphorus(0));
        $this->assertEquals(500, $this->goalService->calculatePhosphorus(5));
        $this->assertEquals(1200, $this->goalService->calculatePhosphorus(15));
        $this->assertEquals(700, $this->goalService->calculatePhosphorus(25));
    }

    public function test_calculate_iron()
    {
        $this->assertEquals(11, $this->goalService->calculateIron(0, 'male'));
        $this->assertEquals(7, $this->goalService->calculateIron(2, 'female'));
        $this->assertEquals(10, $this->goalService->calculateIron(5, 'male'));
        $this->assertEquals(11, $this->goalService->calculateIron(15, 'male'));
        $this->assertEquals(15, $this->goalService->calculateIron(15, 'female'));
        $this->assertEquals(9, $this->goalService->calculateIron(25, 'male'));
        $this->assertEquals(18, $this->goalService->calculateIron(30, 'female'));
        $this->assertEquals(8, $this->goalService->calculateIron(55, 'female'));
    }

    /** @test */
    public function test_calculates_sodium_correctly()
    {
        $this->assertEquals(370, $this->goalService->calculateSodium(0, 'male'));
        $this->assertEquals(800, $this->goalService->calculateSodium(2, 'female'));
        $this->assertEquals(1500, $this->goalService->calculateSodium(20, 'male'));
        $this->assertEquals(1600, $this->goalService->calculateSodium(17, 'female'));
        $this->assertEquals(1700, $this->goalService->calculateSodium(17, 'male'));
    }

    /** @test */
    public function test_calculates_potassium_correctly()
    {
        $this->assertEquals(700, $this->goalService->calculatePotassium(0, 'female'));
        $this->assertEquals(2600, $this->goalService->calculatePotassium(2, 'male'));
        $this->assertEquals(4800, $this->goalService->calculatePotassium(14, 'female'));
        $this->assertEquals(5300, $this->goalService->calculatePotassium(17, 'male'));
    }

    /** @test */
    public function test_calculates_copper_correctly()
    {
        $this->assertEquals(220, $this->goalService->calculateCopper(0));
        $this->assertEquals(340, $this->goalService->calculateCopper(2));
        $this->assertEquals(700, $this->goalService->calculateCopper(11));
        $this->assertEquals(900, $this->goalService->calculateCopper(25));
    }

    /** @test */
    public function test_calculates_zinc_correctly()
    {
        $this->assertEquals(3, $this->goalService->calculateZinc(2, 'female'));
        $this->assertEquals(5, $this->goalService->calculateZinc(6, 'male'));
        $this->assertEquals(8, $this->goalService->calculateZinc(10, 'female'));
        $this->assertEquals(11, $this->goalService->calculateZinc(14, 'male'));
        $this->assertEquals(9, $this->goalService->calculateZinc(15, 'female'));
    }

    /** @test */
    public function test_calculates_retinol_correctly()
    {
        $this->assertEquals(400, $this->goalService->calculateRetinol(2, 'male'));
        $this->assertEquals(600, $this->goalService->calculateRetinol(12, 'female'));
        $this->assertEquals(700, $this->goalService->calculateRetinol(17, 'male'));
        $this->assertEquals(650, $this->goalService->calculateRetinol(25, 'male'));
    }

    /** @test */
    public function test_calculates_beta_carotene_correctly()
    {
        $this->assertEquals(6, $this->goalService->calculateBetaCarotene(10));
        $this->assertEquals(15, $this->goalService->calculateBetaCarotene(15));
    }

    /** @test */
    public function test_calculates_thiamine_correctly()
    {
        $this->assertEquals(0.3, $this->goalService->calculateThiamine(0, 'female'));
        $this->assertEquals(0.9, $this->goalService->calculateThiamine(12, 'male'));
        $this->assertEquals(1.2, $this->goalService->calculateThiamine(20, 'male'));
        $this->assertEquals(1.1, $this->goalService->calculateThiamine(25, 'female'));
    }

    /** @test */
    public function test_calculates_riboflavin_correctly()
    {
        $this->assertEquals(0.3, $this->goalService->calculateRiboflavin(0, 'female'));
        $this->assertEquals(0.9, $this->goalService->calculateRiboflavin(13, 'male'));
        $this->assertEquals(1.3, $this->goalService->calculateRiboflavin(20, 'male'));
        $this->assertEquals(1.1, $this->goalService->calculateRiboflavin(25, 'female'));
    }

    /** @test */
    public function test_calculates_niacin_correctly()
    {
        $this->assertEquals(4, $this->goalService->calculateNiacin(0, 'female'));
        $this->assertEquals(8, $this->goalService->calculateNiacin(5, 'male'));
        $this->assertEquals(16, $this->goalService->calculateNiacin(20, 'male'));
        $this->assertEquals(14, $this->goalService->calculateNiacin(20, 'female'));
    }

    /** @test */
    public function test_calculates_vitamin_c_correctly()
    {
        $this->assertEquals(50, $this->goalService->calculateVitaminC(0, 'male'));
        $this->assertEquals(45, $this->goalService->calculateVitaminC(6, 'female'));
        $this->assertEquals(75, $this->goalService->calculateVitaminC(14, 'male'));
        $this->assertEquals(65, $this->goalService->calculateVitaminC(14, 'female'));
        $this->assertEquals(90, $this->goalService->calculateVitaminC(25, 'male'));
    }

    /** @test */
    public function test_calculates_nutritional_goals_based_on_given_parameters()
    {
        // Prepare the input data
        $gender = 'male';
        $activityLevel = 'sedentary';
        $weight = 75;
        $height = 175;
        $age = 25;

        // Run the method
        $result = $this->goalService->calculateGoal($gender, $activityLevel, $weight, $height, $age);

        // Assert the expected results
        $this->assertArrayHasKey('total_energi', $result);
        $this->assertArrayHasKey('total_protein', $result);
        $this->assertArrayHasKey('total_karbohidrat', $result);
        $this->assertArrayHasKey('total_lemak', $result);
        $this->assertArrayHasKey('total_air', $result);

        $this->assertEquals(2068.5, $result['total_energi']);
        $this->assertEquals(155.1375, $result['total_protein']);
        $this->assertEquals(206.85, $result['total_karbohidrat']);
        $this->assertEquals(68.94999999999999, $result['total_lemak']);
        $this->assertEquals(68.95, $result['total_air']);
    }
}

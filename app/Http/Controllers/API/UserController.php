<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\Food;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Get the authenticated user's profile data.
     *
     * @authenticated
     *
     * @return JsonResponse
     */
    public function showUserProfile(): JsonResponse
    {
        try {
            $user = auth()->user();
            return response()->json([
                'message' => 'User data successfully retrieved',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'tgl_lahir' => $user->tgl_lahir,
                    'no_telp' => $user->no_telp,
                    'gender' => $user->gender,
                    'tinggi_badan' => $user->tinggi_badan,
                    'berat_badan' => $user->berat_badan,
                    'tingkat_aktivitas' => $user->tingkat_aktivitas,
                    'status' => $user->status
                ]
            ], Response::HTTP_OK);

        }catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Something went wrong while retrieving user data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateUserProfile(Request $request)
    {
        $authUserId = $this->authUserId();
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $authUserId,
            'password' => 'required|string|min:6',
            'tgl_lahir' => 'required|date',
            'no_telp' => 'required|string',
            'gender' => ['required',Rule::in(['male', 'female'])],
            'tinggi_badan' => 'required|numeric',
            'berat_badan' => 'required|numeric',
            'tingkat_aktivitas' => ['required',Rule::in(['sedentary', 'lightly_active', 'moderately_active ', 'very_active', 'extra_active'])]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $genderTDEE = $this->getGenderTDEE($request->gender);
        $activityLevelValue = $this->getActivityLevelMultiplier($request->tingkat_aktivitas);
        $bmr = $this->getBMRValue($activityLevelValue);

        $tdee = $bmr * $activityLevel;
        $protein = $tdee * 30/100 / 4;
        $carb = $tdee * 40/100 / 4;
        $fat = $tdee * 30/100 / 9;

        $air = $request->berat_badan / 30;
        $serat = $tdee / 1000 * 14;
        $kalsium = $this->hitungKalsium($ageVal);
        $fosfor = $this->hitungFosfor($ageVal);
        $besi = $this->hitungZatBesi($ageVal, $gender);
        $natrium = $this->hitungNatrium($ageVal, $gender);
        $kalium = $this->hitungKalium($ageVal, $gender);
        $tembaga = $this->hitungTembaga($ageVal);
        $seng = $this->hitungSeng($ageVal, $gender);
        $retinol = $this->hitungRetinol($ageVal, $gender);
        $bKaroten = $this->hitungBKaroten($ageVal);
        $thiamin = $this->hitungThiamin($ageVal, $gender);
        $riboflamin = $this->hitungRiboflamin($ageVal, $gender);
        $niasin = $this->hitungNiasin($ageVal, $gender);
        $vitC = $this->hitungVitC($ageVal, $gender);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'tgl_lahir' => $request->tgl_lahir,
            'gender' => $request->gender,
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan,
            'tingkat_aktivitas' => $request->tingkat_aktivitas
        ]);

            $user->goals()->update([
                'total_air' => $air,
                'total_energi' => $tdee,
                'total_protein' => $protein,
                'total_lemak' => $fat,
                'total_karbohidrat' => $carb,
                'total_serat' => $serat,
                'total_abu' => null,
                'total_kalsium' => $kalsium,
                'total_fosfor' => $fosfor,
                'total_besi' => $besi,
                'total_natrium' => $natrium,
                'total_kalium' => $kalium,
                'total_tembaga' => $tembaga,
                'total_seng' => $seng,
                'total_retinol' => $retinol,
                'total_b_karoten' => $bKaroten,
                'total_karoten_total' => $bKaroten,
                'total_thiamin' => $thiamin,
                'total_riboflamin' => $riboflamin,
                'total_niasin' => $niasin,
                'total_vitamin_c' => $vitC
            ]);

            $user->goalHistories()->create([
                'total_air' => $air,
                'total_energi' => $tdee,
                'total_protein' => $protein,
                'total_lemak' => $fat,
                'total_karbohidrat' => $carb,
                'total_serat' => $serat,
                'total_abu' => null,
                'total_kalsium' => $kalsium,
                'total_fosfor' => $fosfor,
                'total_besi' => $besi,
                'total_natrium' => $natrium,
                'total_kalium' => $kalium,
                'total_tembaga' => $tembaga,
                'total_seng' => $seng,
                'total_retinol' => $retinol,
                'total_b_karoten' => $bKaroten,
                'total_karoten_total' => $bKaroten,
                'total_thiamin' => $thiamin,
                'total_riboflamin' => $riboflamin,
                'total_niasin' => $niasin,
                'total_vitamin_c' => $vitC
            ]);


        return response()->json([
            'message' => 'Data successfully updated',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'tgl_lahir' => $user->tgl_lahir,
                'no_telp' => $user->no_telp,
                'gender' => $user->gender,
                'tinggi_badan' => $user->tinggi_badan,
                'berat_badan' => $user->berat_badan,
                'tingkat_aktivitas' => $user->tingkat_aktivitas
            ]
        ], Response::HTTP_OK);
    }







    public function doctorIndex()
    {
        $doctor = User::whereRoleIs('doctor')->get();;
        return response($doctor, Response::HTTP_OK);
    }

    public function showDoctor(User $doctor)
    {
        return response($doctor, Response::HTTP_OK);
    }

    public function updateStatusDoctor(User $doctor, Request $request)
    {
        $doctorData = $doctor->whereRoleIs('doctor')->find($doctor->id);

        if ($doctorData != null){
            $validator = Validator::make($request->all(), [
                'status' => [
                    'required',
                    'string',
                    Rule::in(['active', 'inactive'])
                ]
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $doctor->update([
                'status' => $request->status
            ]);

            return response($doctor, Response::HTTP_OK);
        }
        else{
            return response()->json(['message' => 'User is not doctor'], Response::HTTP_UNAUTHORIZED);
        }
    }

    private function hitungFosfor($umur)
    {
        if ($umur < 1){
            $fosfor = 250;
        }elseif ($umur >= 1 && $umur <= 9){
            $fosfor = 500;
        }elseif ($umur >= 10 && $umur <= 18){
            $fosfor = 1200;
        }elseif ($umur >= 19) {
            $fosfor = 700;
        }
        return $fosfor;
    }

    private function hitungZatBesi($umur, $gender)
    {
        if ($umur < 1){
            $besi = 11;
        }elseif ($umur >= 1 && $umur <= 3){
            $besi = 7;
        }elseif ($umur >= 4 && $umur <= 9) {
            $besi = 10;
        }elseif (($umur >= 10 && $umur <= 12)||($umur >= 50 && $gender == 'female')) {
            $besi = 8;
        }elseif ($umur >= 19 && $gender == 'male'){
            $besi = 9;
        }elseif ($umur >= 13 && $umur <= 18 && $gender == 'male'){
            $besi = 11;
        }elseif ($umur >= 13 && $umur <= 18 && $gender == 'female'){
            $besi = 15;
        }elseif ($umur >= 19 && $umur <= 49 && $gender == 'female') {
            $besi = 18;
        }
        return $besi;
    }

    private function hitungNatrium($umur, $gender)
    {
        if ($umur < 1){
            $natrium = 370;
        }elseif ($umur >= 1 && $umur <= 3){
            $natrium = 800;
        }elseif ($umur >= 4 && $umur <= 6) {
            $natrium = 900;
        }elseif (($umur >= 7 && $umur <= 9)||($umur >= 80)) {
            $natrium = 1000;
        }elseif ((($umur >= 10 && $umur <= 12)||($umur >= 50 && $umur <= 64)) && $gender == 'male'){
            $natrium = 1300;
        }elseif ($umur >= 65 && $umur <= 80 && $gender == 'male'){
            $natrium = 1100;
        }elseif ($umur >= 65 && $umur <= 80 && $gender == 'female'){
            $natrium = 1200;
        }elseif ((($umur >= 10 && $umur <= 12)||($umur >= 50 && $umur <= 64)) && $gender == 'female'){
            $natrium = 1400;
        }elseif (($umur >= 13 && $umur <= 15) || ($umur >= 19 && $umur <= 49)){
            $natrium = 1500;
        }elseif ($umur >= 16 && $umur <= 18 && $gender == 'female'){
            $natrium = 1600;
        }elseif ($umur >= 16 && $umur <= 18 && $gender == 'male') {
            $natrium = 1700;
        }
        return $natrium;
    }

    private function hitungKalium($umur, $gender)
    {
        if ($umur < 1){
            $kalium = 700;
        }elseif ($umur >= 1 && $umur <= 3){
            $kalium = 2600;
        }elseif ($umur >= 4 && $umur <= 6) {
            $kalium = 2700;
        }elseif ($umur >= 7 && $umur <= 9) {
            $kalium = 3200;
        }elseif ($umur >= 10 && $umur <= 12 && $gender == 'male'){
            $kalium = 3900;
        }elseif ($umur >= 10 && $umur <= 12 && $gender == 'female'){
            $kalium = 4400;
        }elseif ($umur >= 13 && $umur <= 15){
            $kalium = 4800;
        }elseif ($umur >= 16 && $umur <= 18 && $gender == 'male'){
            $kalium = 5300;
        }elseif ($umur >= 16 && $umur <= 18 && $gender == 'female'){
            $kalium = 5000;
        }elseif ($umur >= 19){
            $kalium = 4700;
        }
        return $kalium;
    }

    private function hitungTembaga($umur)
    {
        if ($umur < 1){
            $tembaga = 220;
        }elseif ($umur >= 1 && $umur <= 3){
            $tembaga = 340;
        }elseif ($umur >= 4 && $umur <= 6) {
            $tembaga = 440;
        }elseif ($umur >= 7 && $umur <= 9) {
            $tembaga = 570;
        }elseif ($umur >= 10 && $umur <= 12){
            $tembaga = 700;
        }elseif ($umur >= 13 && $umur <= 15){
            $tembaga = 795;
        }elseif ($umur >= 16 && $umur <= 18){
            $tembaga = 890;
        }elseif ($umur >= 19){
            $tembaga = 900;
        }
        return $tembaga;
    }

    private function hitungSeng($umur, $gender)
    {
        if ($umur <= 3){
            $seng = 3;
        }elseif ($umur >= 4 && $umur <= 9) {
            $seng = 5;
        }elseif (($umur >= 10 && $umur <= 12)||($umur >= 19 && $gender == 'female')){
            $seng = 8;
        }elseif ($umur >= 13 && $gender == 'male'){
            $seng = 11;
        }elseif ($umur >= 13 && $umur <= 18 && $gender == 'female'){
            $seng = 9;
        }
        return $seng;
    }

    private function hitungRetinol($umur, $gender)
    {
        if ($umur <= 3){
            $retinol = 400;
        }elseif ($umur >= 4 && $umur <= 6) {
            $retinol = 450;
        }elseif ($umur >= 7 && $umur <= 9) {
            $retinol = 500;
        }elseif (($umur >= 10 && $umur <= 15 && $gender == 'male')||($umur >= 10 && $gender == 'female')){
            $retinol = 600;
        }elseif ($umur >= 16 && $umur <= 18 && $gender == 'male'){
            $retinol = 700;
        }elseif ($umur >= 19 && $gender == 'male'){
            $retinol = 650;
        }
        return $retinol;
    }

    private function hitungBKaroten($umur)
    {
        if ($umur <= 12) {
            $bKaroten = 6;
        }elseif ($umur >= 13){
            $bKaroten = 15;
        }
        return $bKaroten;
    }

    private function hitungThiamin($umur, $gender)
    {
        if ($umur < 1) {
            $thiamin = 0.3;
        }elseif ($umur >= 1 && $umur <= 3){
            $thiamin = 0.5;
        }elseif ($umur >= 4 && $umur <= 8){
            $thiamin = 0.6;
        }elseif ($umur >= 9 && $umur <= 13){
            $thiamin = 0.9;
        }elseif ($umur >= 14 && $gender == 'male'){
            $thiamin = 1.2;
        }elseif ($umur >= 14 && $umur <= 18 && $gender == 'female'){
            $thiamin = 1;
        }elseif ($umur >= 19 && $gender == 'female'){
            $thiamin = 1.1;
        }
        return $thiamin;
    }

    private function hitungRiboflamin($umur, $gender)
    {
        if ($umur < 1) {
            $riboflamin = 0.3;
        }elseif ($umur >= 1 && $umur <= 3){
            $riboflamin = 0.5;
        }elseif ($umur >= 4 && $umur <= 8){
            $riboflamin = 0.6;
        }elseif ($umur >= 9 && $umur <= 13){
            $riboflamin = 0.9;
        }elseif ($umur >= 14 && $gender == 'male'){
            $riboflamin = 1.3;
        }elseif ($umur >= 14 && $umur <= 18 && $gender == 'female'){
            $riboflamin = 1;
        }elseif ($umur >= 19 && $gender == 'female'){
            $riboflamin = 1.1;
        }
        return $riboflamin;
    }

    private function hitungNiasin($umur, $gender)
    {
        if ($umur < 1) {
            $niasin = 4;
        }elseif ($umur >= 1 && $umur <= 3){
            $niasin = 6;
        }elseif ($umur >= 4 && $umur <= 8){
            $niasin = 8;
        }elseif ($umur >= 9 && $umur <= 13){
            $niasin = 12;
        }elseif ($umur >= 14 && $gender == 'male'){
            $niasin = 16;
        }elseif ($umur >= 14 && $gender == 'female'){
            $niasin = 14;
        }
        return $niasin;
    }

    private function hitungVitC($umur, $gender)
    {
        if (($umur < 1) || ($umur >= 10 && $umur <= 12)) {
            $vitC = 50;
        }elseif ($umur >= 1 && $umur <= 3){
            $vitC = 40;
        }elseif ($umur >= 4 && $umur <= 9){
            $vitC = 45;
        }elseif (($umur >= 13 && $umur <= 15 && $gender == 'male')||($umur >= 16 && $gender == 'female')){
            $vitC = 75;
        }elseif ($umur >= 13 && $umur <= 15 && $gender == 'female'){
            $vitC = 65;
        }elseif ($umur >= 16 && $gender == 'male'){
            $vitC = 90;
        }
        return $vitC;
    }
}

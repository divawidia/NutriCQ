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

            $user->goal()->update([
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

}

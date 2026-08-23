<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\JsonResponse;

class CompanyProfileController extends Controller
{
    public function show(): JsonResponse
    {
        $profile = CompanyProfile::first();

        if (!$profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Company profile not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $profile,
        ]);
    }
}

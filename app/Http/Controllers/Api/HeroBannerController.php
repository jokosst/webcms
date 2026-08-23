<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroBanner;
use Illuminate\Http\JsonResponse;

class HeroBannerController extends Controller
{
    public function index(): JsonResponse
    {
        $banners = HeroBanner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $banners,
        ]);
    }
}

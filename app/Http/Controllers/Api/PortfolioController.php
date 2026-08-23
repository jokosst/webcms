<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Portfolio::with('category')->where('is_active', true);

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->query('category'));
            });
        }

        $portfolios = $query->orderBy('sort_order', 'asc')->latest('completion_date')->paginate($request->query('per_page', 12));

        return response()->json([
            'status' => 'success',
            'data' => $portfolios,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $portfolio = Portfolio::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$portfolio) {
            return response()->json([
                'status' => 'error',
                'message' => 'Portfolio project not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $portfolio,
        ]);
    }
}

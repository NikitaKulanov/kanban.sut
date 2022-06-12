<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Right;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RightController extends Controller
{
    /**
     * Create controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Gate::authorize('rights-check', 'administrator');
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json([], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        return response()->json([], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Right $right
     * @return JsonResponse
     */
    public function show(Right $right)
    {
        return response()->json([], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Right $right
     * @return JsonResponse
     */
    public function update(Request $request, Right $right)
    {
        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Right $right
     * @return JsonResponse
     */
    public function destroy(Right $right)
    {
        return response()->json([], 200);
    }
}

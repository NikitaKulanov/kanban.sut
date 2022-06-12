<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\DescriptionTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DescriptionTaskController extends Controller
{
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
     * @param DescriptionTask $descriptionTask
     * @return JsonResponse
     */
    public function show(DescriptionTask $descriptionTask)
    {
        return response()->json([], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param DescriptionTask $descriptionTask
     * @return JsonResponse
     */
    public function update(Request $request, DescriptionTask $descriptionTask)
    {
        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DescriptionTask $descriptionTask
     * @return JsonResponse
     */
    public function destroy(DescriptionTask $descriptionTask)
    {
        return response()->json([], 200);
    }
}

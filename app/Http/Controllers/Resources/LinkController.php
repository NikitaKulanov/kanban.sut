<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LinkController extends Controller
{
    /**
     * Create controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Link::class, 'link');
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
     * @param Link $link
     * @return JsonResponse
     */
    public function show(Link $link)
    {
        return response()->json([], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Link $link
     * @return JsonResponse
     */
    public function update(Request $request, Link $link)
    {
        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Link $link
     * @return JsonResponse
     */
    public function destroy(Link $link)
    {
        return response()->json([], 200);
    }
}

<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Create controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Message::class, 'message');
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
     * @param Message $message
     * @return JsonResponse
     */
    public function show(Message $message)
    {
        return response()->json([], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Message $message
     * @return JsonResponse
     */
    public function update(Request $request, Message $message)
    {
        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Message $message
     * @return JsonResponse
     */
    public function destroy(Message $message)
    {
        return response()->json([], 200);
    }
}

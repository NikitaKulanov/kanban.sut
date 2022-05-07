<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get information for kanban-board student board
     *
     * @return JsonResponse
     */
    public function getTasksForStudent(): JsonResponse
    {
        return response()->json([], 200);
    }

    /**
     * Get information for kanban-board teaching board, group on a particular discipline
     *
     * @param Discipline $discipline
     * @param Group $group
     * @return JsonResponse
     */
    public function getStudentsForProfessor(Discipline $discipline, Group $group): JsonResponse
    {
        return response()->json([], 200);
    }

    /**
     * Get information for kanban-board teacher board, discipline group
     *
     * @return JsonResponse
     */
    public function getGroupsForProfessor(): JsonResponse
    {
        return response()->json([], 200);
    }
}

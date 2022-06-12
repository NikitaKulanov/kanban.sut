<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Group;
use App\Models\User;
use App\Services\KanbanService;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class KanbanController extends Controller
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var KanbanService
     */
    private KanbanService $kanbanService;

    /**
     * @param Request $request
     * @param KanbanService $kanbanService
     */
    public function __construct(Request $request, KanbanService $kanbanService)
    {
        $this->request = $request;
        $this->kanbanService = $kanbanService;
    }

    /**
     * Get information for kanban-board student board
     *
     * @return JsonResponse
     */
    public function getTasksForStudent(): JsonResponse
    {
        $this->rightsCheck('student');
        return response()->json($this->kanbanService->getTasksForStudent($this->request->user()));
//        return response()->json([
//            'forbiddenStatuses' => [
//                5
//            ],
//            'statuses' => [
//                [
//                    'id' => 1,
//                    'title' => 'К ВЫПОЛНЕНИЮ'
//                ],
//                [
//                    'id' => 2,
//                    'title' => 'В РАБОТЕ'
//                ],
//                [
//                    'id' => 3,
//                    'title' => 'НА ОБСУЖДЕНИЕ'
//                ],
//                [
//                    'id' => 4,
//                    'title' => 'НА ПРОВЕРКЕ'
//                ],
//                [
//                    'id' => 5,
//                    'title' => 'ГОТОВО'
//                ],
//            ],
//            'rows' => [
//                [
//                    'id' => 0,
//                    'first_title' => 'СОКР',
//                    'second_title' => 'Полное'
//                ],
//                [
//                    'id' => 1,
//                    'first_title' => 'ФИЗКУЛЬТУРА',
//                    'second_title' => 'ИЗО'
//                ],
//                [
//                    'id' => 2,
//                    'first_title' => 'ГЕОРГРАФИЯ',
//                    'second_title' => 'ИЗО'
//                ]
//            ],
//            'cards' => [
//                [
//                    'id' => 11,
//                    'title' => '1 задание',
//                    'theme' => 'Лучший',
//                    'statusId' => 1,
//                    'sectionId' => 0,
//                    "deadline" => "26.05.2022",
//                    "deadline_timestamp" => (new DateTime("26.05.2022"))->getTimestamp(),
//                ],
//                [
//                    'id' => 89,
//                    'title' => '1 задание',
//                    'theme' => 'Лучший',
//                    'statusId' => 5,
//                    'sectionId' => 0,
//                    "deadline" => "26.05.2022",
//                    "deadline_timestamp" => (new DateTime("26.05.2022"))->getTimestamp(),
//                ],
//                [
//                    'id' => 1,
//                    'title' => '0 задание',
//                    'theme' => 'Лучший',
//                    'statusId' => 1,
//                    'sectionId' => 0,
//                    "deadline" => "25.05.2022",
//                    "deadline_timestamp" => (new DateTime("25.05.2022"))->getTimestamp(),
//                ],
//                [
//                    'id' => 10,
//                    'title' => '123 задание',
//                    'theme' => 'Лучший',
//                    'statusId' => 1,
//                    'sectionId' => 0,
//                    "deadline" => "24.05.2022",
//                    "deadline_timestamp" => (new DateTime("24.05.2022"))->getTimestamp(),
//                ],
//                [
//                    'id' => 2,
//                    'title' => '2 задание',
//                    'theme' => 'Лучший',
//                    'statusId' => 2,
//                    'sectionId' => 1,
//                    "deadline" => '23.05.2022',
//                    "deadline_timestamp" => (new DateTime('23.05.2022'))->getTimestamp(),
//                ],
//                [
//                    'id' => 3,
//                    'title' => '3 задание API',
//                    'theme' => 'Лучший',
//                    'statusId' => 3,
//                    'sectionId' => 2,
//                    "deadline" => "22.05.2022",
//                    "deadline_timestamp" => (new DateTime("22.05.2022"))->getTimestamp(),
//                ],
//            ]
//        ], 200);
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
        $this->rightsCheck('professor');

        return response()->json($this->kanbanService->getStudentsForProfessor($discipline, $group));

//        return response()->json([
//            'forbiddenStatuses' => [
//                1, 2, 3
//            ],
//            'statuses' => [
//                [
//                    'id' => 1,
//                    'title' => 'К ВЫПОЛНЕНИЮ'
//                ],
//                [
//                    'id' => 2,
//                    'title' => 'В РАБОТЕ'
//                ],
//                [
//                    'id' => 3,
//                    'title' => 'НА ОБСУЖДЕНИЕ'
//                ],
//                [
//                    'id' => 4,
//                    'title' => 'НА ПРОВЕРКЕ'
//                ],
//                [
//                    'id' => 5,
//                    'title' => 'ГОТОВО'
//                ],
//            ],
//            'rows' => [
//                [
//                    'id' => 0,
//                    'first_title' => 'Куланов',
//                    'second_title' => 'Никита'
//                ],
//                [
//                    'id' => 1,
//                    'first_title' => 'Пономарёв',
//                    'second_title' => 'Егор'
//                ],
//                [
//                    'id' => 2,
//                    'first_title' => 'Расулов',
//                    'second_title' => 'Мурад'
//                ]
//            ],
//            'cards' => [
//                [
//                    'id' => 11,
//                    'title' => '1 задание',
//                    'theme' => 'Лучший',
//                    'statusId' => 1,
//                    'sectionId' => 0,
//                    "deadline" => "26.05.2022",
//                    "deadline_timestamp" => (new DateTime("26.05.2022"))->getTimestamp(),
//                ],
//                [
//                    'id' => 89,
//                    'title' => '1 задание',
//                    'theme' => 'Лучший',
//                    'statusId' => 5,
//                    'sectionId' => 0,
//                    "deadline" => "26.05.2022",
//                    "deadline_timestamp" => (new DateTime("26.05.2022"))->getTimestamp(),
//                ],
//                [
//                    'id' => 1,
//                    'title' => '0 задание',
//                    'theme' => 'Лучший',
//                    'statusId' => 1,
//                    'sectionId' => 0,
//                    "deadline" => "25.05.2022",
//                    "deadline_timestamp" => (new DateTime("25.05.2022"))->getTimestamp(),
//                ],
//                [
//                    'id' => 10,
//                    'title' => '123 задание',
//                    'theme' => 'Лучший',
//                    'statusId' => 1,
//                    'sectionId' => 0,
//                    "deadline" => "24.05.2022",
//                    "deadline_timestamp" => (new DateTime("24.05.2022"))->getTimestamp(),
//                ],
//                [
//                    'id' => 2,
//                    'title' => '2 задание',
//                    'theme' => 'Лучший',
//                    'statusId' => 2,
//                    'sectionId' => 1,
//                    "deadline" => '23.05.2022',
//                    "deadline_timestamp" => (new DateTime('23.05.2022'))->getTimestamp(),
//                ],
//                [
//                    'id' => 3,
//                    'title' => '3 задание API',
//                    'theme' => 'Лучший',
//                    'statusId' => 3,
//                    'sectionId' => 2,
//                    "deadline" => "22.05.2022",
//                    "deadline_timestamp" => (new DateTime("22.05.2022"))->getTimestamp(),
//                ],
//            ]
//        ], 200);
    }

    /**
     * Get information for kanban-board teacher board, discipline group
     *
     * @return JsonResponse
     */
    public function getGroupsForProfessor(): JsonResponse
    {
        $this->rightsCheck('professor');

        return response()->json($this->kanbanService->getGroupsForProfessor($this->request->user()));

//        return response()->json([
//            [
//                "year" => 1,
//                "groups" => [
//                    [
//                        "id" => 7,
//                        "title" => "ИСТ-234",
//                        "disciplines" => [
//                            [
//                                "id" => 7,
//                                "abbreviation" => "ИСС",
//                                "title" => "Инфокоммуникационные системы и сети"
//                            ]
//                        ]
//                    ],
//                    [
//                        "id" => 6,
//                        "title" => "ИСТ-231",
//                        "disciplines" => [
//                            [
//                                "id" => 8,
//                                "abbreviation" => "ТОС",
//                                "title" => "Технологии объединённых сетей"
//                            ]
//                        ]
//                    ]
//                ]
//            ],
//            [
//                "year" => 2,
//                "groups" => [
//                    [
//                        "id" => 5,
//                        "title" => "ИСТ-132",
//                        "disciplines" => [
//                            [
//                                "id" => 7,
//                                "abbreviation" => "ИСС",
//                                "title" => "Инфокоммуникационные системы и сети"
//                            ]
//                        ]
//                    ]
//                ]
//            ],
//            [
//                "year" => 3,
//                "groups" => [
//                    [
//                        "id" => 4,
//                        "title" => "ИСТ-930",
//                        "disciplines" => [
//                            [
//                                "id" => 5,
//                                "abbreviation" => "АИСС",
//                                "title" => "Архитектура информационных систем связи"
//                            ]
//                        ]
//                    ]
//                ]
//            ],
//            [
//                "year" => 4,
//                "groups" => [
//                    [
//                        "id" => 3,
//                        "title" => "ИСТ-831",
//                        "disciplines" => [
//                            [
//                                "id" => 4,
//                                "abbreviation" => "МКС",
//                                "title" => "Масштабирование компьютерных сетей"
//                            ],
//                            [
//                                "id" => 6,
//                                "abbreviation" => "ТОС",
//                                "title" => "Технологии объединённых сетей"
//                            ]
//                        ]
//                    ]
//                ]
//            ]
//        ], 200);
    }

    /**
     * @param string $role
     * @return void
     */
    private function rightsCheck(string $role)
    {
        Gate::authorize('rights-check', $role);
    }
}

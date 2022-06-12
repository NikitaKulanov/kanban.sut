<?php

namespace App\Services;

use App\Events\AddTaskKanban;
use App\Events\EditTaskStatus;
use App\Models\DescriptionTask;
use App\Models\Discipline;
use App\Models\Group;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use DateTime;

class KanbanService
{
    /**
     * @param Task $task
     * @param int $statusId
     * @return void
     */
    public function editTaskStatus(Task $task, int $statusId)
    {
        $task->status_id = $statusId;
        $task->save();// Успешно ли...

        EditTaskStatus::dispatch($task->id, $task->status);
    }

    /**
     * @param Task $task
     * @param string|int $estimate
     * @return void
     */
    public function editTaskEstimate(Task $task, string|int $estimate) // В БД estimate string
    {
        $task->estimate = $estimate;
        $task->save();
    }

    /**
     * @param int $author_id
     * @param int $discipline_id
     * @param string $title
     * @param string $theme
     * @param string $description
     * @param string $deadline
     * @param int $group_id
     * @return array[]
     */
    public function addTasksForStudents(
        int $author_id,
        int $discipline_id,
        string $title,
        string $theme,
        string $description,
        string $deadline,
        int $group_id
    ) : array
    {
        $descriptionTask = new DescriptionTask();
        $descriptionTask->author_id = $author_id;
        $descriptionTask->discipline_id = $discipline_id;
        $descriptionTask->title = $title;
        $descriptionTask->theme = $theme;
        $descriptionTask->description = $description;
        $descriptionTask->deadline = (new DateTime($deadline))->format('Y-m-d H:i:s');
        $descriptionTask->appointment = (new DateTime())->format('Y-m-d H:i:s');
        $descriptionTask->is_public = true; // Временно
        $descriptionTask->save();

        $tasksId = [];
        foreach ($this->getStudentsUserId($group_id) as $studentUserId) {
            $task = new Task;
            $task->status_id = 1; // В константу
            $task->perpetrator_id = $studentUserId;
            $task->description_task_id = $descriptionTask->id;
            $task->save();
            $tasksId[] = $task->id;
            AddTaskKanban::dispatch([
                'id' => $task->id,
                'title' => $descriptionTask->title,
                'theme' => $descriptionTask->theme,
                'statusId' => $task->status_id,
                'sectionId' => null,
                'deadline' => (new DateTime($descriptionTask->deadline))->format('d.m.Y'),
                'deadline_timestamp' => (new DateTime($descriptionTask->deadline))->getTimestamp(),
            ],
                $studentUserId, // Это временно
                $author_id,
                $discipline_id
            );
        }
        return ['tasks_id' => $tasksId];
    }

    /**
     * @param User $user
     * @return array
     */
    public function getGroupsForProfessor(User $user): array
    {
        /**
         *  TODO "Метод старый и не оптимизированный"
         */

        $body = [
            [
                'year' => 1,
                'groups_year' => []
            ],
            [
                'year' => 2,
                'groups_year' => []
            ],
            [
                'year' => 3,
                'groups_year' => []
            ],
            [
                'year' => 4,
                'groups_year' => []
            ],
        ];

        $disciplinesProfessor = [];
        $groupsCollection = collect();
        foreach ($user->professor->disciplines as $discipline) {
            $disciplinesProfessor[] = $discipline->id;
            foreach ($discipline->groups as $group) {
                $groupsCollection->push($group);
            }
        }

        foreach ($groupsCollection->unique('id')->values() as $group) {
            $body[$group->year - 1]['groups'][] = [
                "id" => $group->id,
                "title" => $group->title,
                "disciplines" => $this->getDisciplinesGroup($group->id, $disciplinesProfessor)
                    ->makeHidden([
                        'pivot'
                    ])
            ];
        }

        return $body;
    }

    /**
     * @param Discipline $discipline
     * @param Group $group
     * @return array
     */
    public function getStudentsForProfessor(Discipline $discipline, Group $group): array
    {
        $students = Group::find($group->id)->students;
        $descriptionsTask = DescriptionTask::Where('discipline_id', $discipline->id)->get();
        $body = $this->getBodyForKanban();

        array_push($body['forbiddenStatuses'], 1, 2, 3);// Пока так

        $body['statuses'] = Status::all();

        foreach ($students as $student) {
            $user = $student->user;
            $body['rows'][] = [
                'id' => $user->id,
                'first_title' => $user->name,
                'second_title' => $user->surname
            ];

            foreach ($descriptionsTask as $descriptionTask) {
                $requestDescriptionTask = $descriptionTask->tasks->where('perpetrator_id', $user->id);
                if ($requestDescriptionTask) {
                    foreach ($requestDescriptionTask as $task) {
                        $body['cards'][] = [
                            'id' => $task->id,
                            'title' => $descriptionTask->title,
                            'theme' => $descriptionTask->theme,
                            'statusId' => $task->status_id,
                            'sectionId' => $user->id,
                            'deadline' => (new DateTime($descriptionTask->deadline))->format('d.m.Y'),
                            'deadline_timestamp' => (new DateTime($descriptionTask->deadline))->getTimestamp(),
                        ];
                    }
                }
            }
        }
        return $body;
    }

    public function getTasksForStudent(User $user)
    {
        $uniqueDT = [];

        $body = $this->getBodyForKanban();
        $body['forbiddenStatuses'][] = 5;// Пока так
        $body['statuses'] = Status::all();
        $tasks = Task::Where('perpetrator_id', $user->id)->get();
        foreach ($tasks as $task) {
            $descriptionTask = $task->descriptionTask;
            $discipline = $task->descriptionTask->discipline;
            if(!in_array($discipline->id, $uniqueDT)) {
                $uniqueDT[] = $discipline->id;
                $body['rows'][] = [
                    'id' => $discipline->id,
                    'first_title' => $discipline->abbreviation,
                    'second_title' => $discipline->title
                ];
            }
            $body['cards'][] = [
                'id' => $task->id,
                'title' => $descriptionTask->title,
                'theme' => $descriptionTask->theme,
                'statusId' => $task->status_id,
                'sectionId' => $discipline->id,
                'deadline' => (new DateTime($descriptionTask->deadline))->format('d.m.Y'),
                'deadline_timestamp' => (new DateTime($descriptionTask->deadline))->getTimestamp(),
            ];
        }

        return $body;
    }

    private function getBodyForKanban(): array
    {
        return [
            'forbiddenStatuses' => [],
            'statuses' => [],
            'rows' => [],
            'cards' => []
        ];
    }

    /**
     * @param int $groupId
     * @param array $disciplinesId
     * @return mixed
     */
    private function getDisciplinesGroup(int $groupId, array $disciplinesId): mixed
    {
        return Group::find($groupId)
            ->disciplines()
            ->whereIn('discipline_id', $disciplinesId)
            ->get();
    }

    /**
     * @param int $groupId
     * @return array
     */
    private function getStudentsUserId(int $groupId): array
    {
        $arrayUserId = [];
        foreach (Group::find($groupId)->students as $student) {
            $arrayUserId[] = $student->user_id;
        }
        return $arrayUserId;
    }
}

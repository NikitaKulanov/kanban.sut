<?php

namespace App\Providers;

use App\Contracts\BindingModelContract;
use App\Models\Task;
use App\Models\User;
use App\Models\UserAccessToTheTask;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * Checking the rights to participate in the task.
         */
        Gate::define('rights-check-for-binding', function (
            User $user,
            Task $task,
            BindingModelContract $model,
            array $rights
        ) {
            if ($user->role === 'administrator') {
                return true;
            } elseif ($user->id === $model->getSenderId()) {
                $task->load('descriptionTask');
                if (in_array($user->id, [$task->perpetrator_id, $task->descriptionTask->author_id])) {
                    return true;
                } else {
                    if ($task->descriptionTask->is_public == true) {
                        return true;
                    } else {
                        if(in_array(
                            UserAccessToTheTask::Where([
                                ['task_id', '=', $task->id],
                                ['user_id', '=', $user->id],
                            ])->first()?->right_id,
                            $rights
                        )) return true;
                    }
                }
            }
            return false;
        });

        /**
         * Rights check.
         */
        Gate::define('rights-check', function (User $user, string $role){
            return $user->role === $role;
        });
    }
}

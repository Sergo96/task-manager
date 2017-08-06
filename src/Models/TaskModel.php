<?php

namespace ToDo\Models;

use ToDo\Helpers\Base;
use ToDo\Repositories\TaskRepository;

class TaskModel extends BaseModel
{
    const ALLOWED_TASKS_STATUSES = ['todo', 'in dev', 'in test', 'done'];

    public function __construct($db)
    {
        parent::__construct($db);

        $this->repository = new TaskRepository($db);
    }

    public function getTasksList(int $page = 1) : array
    {
        $tasks_list = [];
        $page = ($page >= 1 ? $page : 1);
        $offset = ($page - 1) * ITEMS_PER_PAGE;

        $tasks_data = $this->repository->getTasksList($offset, ITEMS_PER_PAGE);

        foreach($tasks_data as $task) {
            $tasks_list[] = [
                'id'            => (int)$task['id'],
                'title'         => $task['title'],
                'status'        => $task['status'],
                'author_name'   => $task['author_name'],
                'author_email'  => $task['email'],
                'image_path'    => Base::getImagePath($task['image_hash']),
            ];
        }

        return [
            'tasks' => $tasks_list,
            'need_pagination' => true, // TODO
        ];
    }
}

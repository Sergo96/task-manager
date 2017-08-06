<?php

namespace ToDo\Models;

use ToDo\Helpers\Base;
use ToDo\Repositories\TaskRepository;
use JasonGrimes\Paginator;


/**
 * @property TaskRepository repository
 */
class TaskModel extends BaseModel
{
    const ALLOWED_TASKS_STATUSES = ['todo', 'in dev', 'in test', 'done'];

    public function __construct($db)
    {
        parent::__construct($db);

        $this->repository = new TaskRepository($db);
    }

    public function getTasksList(?int $page = 1) : array
    {
        $tasks_list = [];
        $page = ($page >= 1 ? $page : 1);
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        $total_tasks_count = $this->repository->getTasksCount();
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

        $pagination = "";
        $need_pagination = $total_tasks_count > ITEMS_PER_PAGE;
        if ($need_pagination) {
            $pagination = new Paginator($total_tasks_count, ITEMS_PER_PAGE, $page, '/(:num)');
            $pagination->setMaxPagesToShow(MAX_PAGES_SHOW);
        }

        return [
            'tasks' => $tasks_list,
            'pagination' => $pagination,
        ];
    }
}

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

    /**
     * @param int|null $page
     *
     * @return array
     */
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
            $pagination = new Paginator(
                $total_tasks_count,
                ITEMS_PER_PAGE,
                $page,
                '/(:num)'
            );
            $pagination->setMaxPagesToShow(MAX_PAGES_SHOW);
        }

        return [
            'tasks' => $tasks_list,
            'pagination' => $pagination,
        ];
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getTaskById(int $id) : array
    {
        $task = $this->repository->getTaskById($id);

        return [
            'id'            => (int)$task['id'],
            'title'         => $task['title'],
            'text'          => $task['text'],
            'status'        => $task['status'],
            'author_name'   => $task['author_name'],
            'author_email'  => $task['email'],
            'image_path'    => Base::getImagePath($task['image_hash'], false),
        ];
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function createTask(array $data) : bool
    {
        $result = false;

        $title = $data['task_title']->value;
        $author_email = $data['author_email']->value;
        $author_name = $data['author_name']->value;
        $task_status = $data['task_status']->value;
        $task_description = $data['task_description']->value;
        $task_image = $data['task_image']->value;

        if ($this->checkTaskParams(
            $title,
            $author_email,
            $author_name,
            $task_status,
            $task_description))
        {
            // TODO process image

            $result = $this->repository->createTask(
                $title,
                $author_email,
                $author_name,
                $task_status,
                $task_description,
                $task_image
            );

            if (!$result) {
                $this->setNotification(
                    "Database error: can not create task",
                    BaseModel::NOTIFICATION_TYPE_ERROR
                );
            } else {
                $this->setNotification('Task created');
            }
        }

        return $result;
    }

    /**
     * @param string $title
     * @param string $author_email
     * @param string $author_name
     * @param string $task_status
     * @param string $task_description
     *
     * @return bool
     */
    protected function checkTaskParams(
        string $title,
        string $author_email,
        string $author_name,
        string $task_status,
        string $task_description
    ) : bool
    {
        $error = "";

        if (in_array('', [$title, $author_email, $author_name, $task_status, $task_description])) {
            $error = "All fields must not be empty";
        } elseif (!in_array($task_status, self::ALLOWED_TASKS_STATUSES)) {
            $error = "Unknown task status: {$task_status}";
        }

        if (!empty($error)) {
            $this->setNotification($error, BaseModel::NOTIFICATION_TYPE_ERROR);

            return false;
        }

        return true;
    }
}

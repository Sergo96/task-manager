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
    const SEARCH_BY_FIELDS = ['status', 'author_name', 'email'];

    public function __construct($db)
    {
        parent::__construct($db);

        $this->repository = new TaskRepository($db);
    }

    /**
     * @param int|null    $page
     *
     * @param null|string $search_by
     * @param null|string $search_string
     *
     * @return array
     */
    public function getTasksList(?int $page = 1, ?string $search_by = '', ?string $search_string = '') : array
    {
        $tasks_list = [];
        $page = ($page >= 1 ? $page : 1);
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        $search_by = (!empty($search_by) && in_array($search_by, self::SEARCH_BY_FIELDS)) ? $search_by : '';
        $total_tasks_count = $this->repository->getTasksCount($search_by, $search_string);
        $tasks_data = $this->repository->getTasksList($offset, ITEMS_PER_PAGE, $search_by, $search_string);

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
                '/(:num)/' . (!empty($search_by) ? $search_by . '/' . $search_string . '/' : '')
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

        $title = trim($data['task_title']->value);
        $author_email = trim($data['author_email']->value);
        $author_name = trim($data['author_name']->value);
        $task_status = trim($data['task_status']->value);
        $task_description = trim($data['task_description']->value);
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
     * @param array $data
     *
     * @return bool
     */
    public function updateTask(array $data) : bool
    {
        $result = false;

        $id = trim((int)$data['id']->value);
        $title = trim($data['task_title']->value);
        $author_email = trim($data['author_email']->value);
        $author_name = trim($data['author_name']->value);
        $task_status = trim($data['task_status']->value);
        $task_description = trim($data['task_description']->value);

        if (AuthModel::isLoggedIn()) {
            if ($this->checkTaskParams(
                $title,
                $author_email,
                $author_name,
                $task_status,
                $task_description
            )) {

                $result = $this->repository->updateTask(
                    $id,
                    $title,
                    $author_email,
                    $author_name,
                    $task_status,
                    $task_description
                );

                if ($result) {
                    $this->setNotification('Task was successfully updated!');
                } else {
                    $this->setNotification('Something goes wrong', BaseModel::NOTIFICATION_TYPE_ERROR);
                }
            }
        } else {
            $this->setNotification("Only admins can edit tasks", BaseModel::NOTIFICATION_TYPE_ERROR);
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

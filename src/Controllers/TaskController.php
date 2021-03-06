<?php

namespace ToDo\Controllers;

use Pecee\SimpleRouter\SimpleRouter;
use ToDo\Helpers\Base;
use ToDo\Models\AuthModel;
use ToDo\Models\TaskModel;

/**
 * @property TaskModel model
 */
class TaskController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = new TaskModel($this->container->db);
    }

    /**
     * @param int         $page
     * @param null|string $order_by
     * @param null|string $search_by
     * @param null|string $search_string
     */
    public function tasksListAction(?int $page = 1, ?string $order_by = 'id', ?string $search_by = '', ?string $search_string = '') : void
    {
        $data = $this->model->getTasksList($page, $order_by, $search_by, $search_string);

        $this->container->twig->display('tasks_list.html.twig', [
            'tasks' => $data['tasks'],
            'saved_data' => [
                'page' => $data['page'],
                'order_by' => $data['order_by'],
                'search_by' => $search_by,
                'search_string' => $search_string,
            ],
            'pagination' => $data['pagination'],
        ]);
    }

    /**
     * @param int $id
     */
    public function taskByIdAction(int $id) : void
    {
        $data = $this->model->getTaskById($id);

        $this->container->twig->display('task_full.html.twig', [
            'task_data' => $data,
        ]);
    }

    public function createTaskAction() : void
    {
        $this->container->twig->display('create_task.html.twig');
    }
    
    public function createTask() : void
    {
        $params = SimpleRouter::request()->getInput()->post;

        $this->model->createTask($params);
        $_SESSION['notifications'] = $this->model->getNotifications();

        Base::redirectTo('/create');
    }

    /**
     * @param int $id
     */
    public function editTaskAction(int $id) : void
    {
        if (AuthModel::isLoggedIn()) {
            $data = $this->model->getTaskById($id);

            $this->container->twig->display('edit_task.html.twig', [
                'task_data' => $data,
            ]);
        } else {
            Base::redirectTo('/');
        }
    }

    public function editTask()
    {
        $params = SimpleRouter::request()->getInput()->post;

        $this->model->updateTask($params);

        $_SESSION['notifications'] = $this->model->getNotifications();

        Base::redirectTo('/edit-task/' . $params['id']->value);
    }
}

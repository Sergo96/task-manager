<?php

namespace ToDo\Controllers;

use Pecee\SimpleRouter\SimpleRouter;
use ToDo\Helpers\Base;
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
     * @param int $page
     */
    public function tasksListAction(?int $page = 1) : void
    {
        $data = $this->model->getTasksList($page);

        $this->container->twig->display('tasks_list.html.twig', [
            'tasks' => $data['tasks'],
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
}

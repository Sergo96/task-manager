<?php

namespace ToDo\Controllers;

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
    public function getTasksList(?int $page = 1)
    {
        $data = $this->model->getTasksList($page);

        $this->container->twig->display('tasks_list.html.twig', [
            'tasks' => $data['tasks'],
            'pagination' => $data['pagination'],
        ]);
    }
}

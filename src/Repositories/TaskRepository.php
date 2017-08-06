<?php

namespace ToDo\Repositories;

class TaskRepository extends BaseRepository
{
    /**
     * @param int $offset
     * @param int $count
     *
     * @return array
     */
    public function getTasksList(int $offset, int $count) : array
    {
        $statement = $this->db->prepare("
SELECT `id`, `title`, `author_name`, `email`, `status`, `image_hash`
FROM `tasks`
ORDER BY `id` DESC
LIMIT :start, :amount"
        );

        $statement->bindParam(':start', $offset, \PDO::PARAM_INT);
        $statement->bindParam(':amount', $count, \PDO::PARAM_INT);

        $statement->execute();


        return $statement->fetchAll();
    }

    /**
     * Get number of total tasks in system
     *
     * @return int
     */
    public function getTasksCount() : int
    {
        $result = $this->db->query("SELECT COUNT(`id`) as 'count' FROM `tasks`");

        return (int)$result->fetch()['count'];
    }
}

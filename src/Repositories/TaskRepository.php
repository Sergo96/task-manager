<?php

namespace ToDo\Repositories;

class TaskRepository extends BaseRepository
{
    public function getTasksList(int $offset, int $count) : array
    {
        $result = $this->db->prepare("
SELECT `id`, `title`, `author_name`, `email`, `status`, `image_hash`
FROM `tasks`
ORDER BY `id` DESC
LIMIT :start, :amount"
        );

        $result->bindParam(':start', $offset, \PDO::PARAM_INT);
        $result->bindParam(':amount', $count, \PDO::PARAM_INT);

        $result->execute();


        return $result->fetchAll();
    }
}

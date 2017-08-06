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

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getTaskById(int $id)
    {
        $statement = $this->db->prepare("
SELECT `id`, `title`, `text`, `author_name`, `email`, `status`, `image_hash`
FROM `tasks`
WHERE `id` = ?
;");
        $statement->execute([$id]);

        return $statement->fetch();
    }

    /**
     * @param string $title
     * @param string $author_email
     * @param string $author_name
     * @param string $status
     * @param string $description
     * @param string $image_hash
     *
     * @return bool
     */
    public function createTask(
        string $title,
        string $author_email,
        string $author_name,
        string $status,
        string $description,
        string $image_hash
    ) : bool
    {
        $statement = $this->db->prepare("
INSERT INTO `tasks` (`title`, `text`, `author_name`, `email`, `status`, `image_hash`)
VALUES (:title, :text, :author_name, :author_email, :status, :image_hash)
");
        return $statement->execute([
           ':title'         => $title,
           ':text'          => $description,
           ':author_name'   => $author_name,
           ':author_email'  => $author_email,
           ':status'        => $status,
           ':image_hash'    => $image_hash,
        ]);
    }
}

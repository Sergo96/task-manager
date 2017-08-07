<?php

namespace ToDo\Repositories;

class TaskRepository extends BaseRepository
{
    /**
     * @param int         $offset
     * @param int         $count
     *
     * @param null|string $search_by
     * @param null|string $search_string
     *
     * @return array
     */
    public function getTasksList(int $offset, int $count, ?string $search_by = '', ?string $search_string = '') : array
    {
        $where = !empty($search_by) ? "WHERE `{$search_by}` LIKE :search_string" : "";
        $sql = "
SELECT `id`, `title`, `author_name`, `email`, `status`, `image_hash`
FROM `tasks`
{$where}
ORDER BY `id` DESC
LIMIT :start, :amount";

        $statement = $this->db->prepare($sql);

        $statement->bindParam(':start', $offset, \PDO::PARAM_INT);
        $statement->bindParam(':amount', $count, \PDO::PARAM_INT);

        if (!empty($search_by)) {
            $search_string = "%$search_string%";
            $statement->bindParam(':search_string', $search_string, \PDO::PARAM_STR);
        }

        $statement->execute();


        return $statement->fetchAll();
    }

    /**
     * Get number of total tasks in system
     *
     * @param null|string $search_by
     * @param null|string $search_string
     *
     * @return int
     */
    public function getTasksCount(?string $search_by = '', ?string $search_string = '') : int
    {
        $where = !empty($search_by) ? "WHERE `{$search_by}` LIKE :search_string" : "";
        $sql = "SELECT COUNT(`id`) as 'count' FROM `tasks` {$where}";
        $result = $this->db->prepare($sql);

        if (!empty($search_by)) {
            $search_string = "%$search_string%";
            $result->bindParam(':search_string', $search_string);
        }

        $result->execute();

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

    public function updateTask(
        int $id,
        ?string $title,
        ?string $author_email,
        ?string $author_name,
        ?string $status,
        ?string $description
    ) : bool
    {
        $statement = $this->db->prepare("
  UPDATE `tasks` 
  SET `title` = :title, `text` = :description, `author_name` = :author_name,
  `email` = :author_email, `status` = :status
  WHERE `id` = :id
        ");

        return $statement->execute([
           ':title' => $title,
           ':description' => $description,
           ':author_name' => $author_name,
           ':author_email' => $author_email,
           ':status' => $status,
           ':id' => $id,
        ]);
    }
}

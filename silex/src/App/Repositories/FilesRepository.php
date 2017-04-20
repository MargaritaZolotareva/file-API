<?php
namespace App\Repositories;
use App\Repositories\BaseRepository;

class FilesRepository extends BaseRepository
{
    /**
     * @param $id
     * @return File
     */
    public function findOneById($id)
    {
        $sql  = "SELECT * FROM files WHERE id = ?";
        $file = $this->connection->fetchAssoc($sql, array(
            (int) $id
        ));
        return $file;
    }
    
    public function findAll()
    {
        $sql   = "SELECT * FROM files";
        $files = $this->connection->fetchAll($sql);
        return $files;
    }
    
    public function findMetadataForFile($id)
    {
        $sql      = "SELECT title, description, mimeType FROM files WHERE id = ?";
        $metadata = $this->connection->fetchAssoc($sql, array(
            (int) $id
        ));
        return $metadata;
    }
    
    public function saveFile($data)
    {
        $this->connection->insert('files', $data);
        $id = $this->connection->lastInsertId();
        return $id;
    }
    
    public function updateFile($data)
    {
        $sql = "UPDATE files SET title = :title, description = :description, mimeType = :mimeType, data = :data WHERE id = :id";
        $this->connection->executeUpdate($sql, $data);
        $updatedFile = $this->findOneById($data['id']);
        return $updatedFile;
    }
    
    protected function getClassName()
    {
        return 'App\Repositories\FilesRepository';
    }
    
    protected function getTableName()
    {
        return 'files';
    }
}
<?php
namespace App\Repositories;
use Doctrine\DBAL\Connection;

abstract class BaseRepository
{
    protected $connection;
    
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}
<?php

namespace App\Models;

class File
{
    public $id;
    public $title;
    public $description;
    public $mimeType;
    public $data;
    
    public function __construct($title = null, $description = null, $mimeType = null, $data = null)
    {
        $this->title       = $title;
        $this->description = $description;
        $this->mimeType    = $mimeType;
        $this->data        = $data;
    }
    
    public function createFromArray($array)
    {
        $file = new self();
        
        $file->id          = $array['id'];
        $file->title       = $array['title'];
        $file->description = $array['description'];
        $file->mimeType    = $array['mimeType'];
        $file->data        = $array['data'];
        return $file;
    }
    
    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }
    
    public function getData()
    {
        return $this->data;
    }
}
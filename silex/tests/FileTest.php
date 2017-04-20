<?php
namespace App\Tests;
use App\Models\File;

class FilesControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFileFromArray()
    {
        $title = 'abc';
        $description = 'abc';
        $mimeType = 'abc';
        $content = 'abc';

        $data = array(
            'title' => $title,
            'description' => $description,
            'mimeType' => $mimeType,
            'data' => $content
        );

        $file = new File($title, $description, $mimeType, $content);
        $fileFromArray = File::createFromArray($data);

        $this->assertEquals($file, $fileFromArray);
    }
}
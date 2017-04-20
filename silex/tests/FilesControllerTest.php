<?php
namespace App\Tests;
require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Exception\RequestException;

class FilesControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFile()
    {
        $client = new Client();

        $data = array(
            'title' => 'verv',
            'description' => 'wefwe',
            'mimeType' => '...',
            'data' => 'mwoepmf'
        );
        $response = $client->post('http://localhost:8001/api/v1/files', [
            'body' => $data
        ]);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $data = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('id', $data);
    }

    public function testCreateFileWithWrongBody()
    {
        $client = new Client();

        $data = array(
            'description' => 'wefwe',
            'data' => 'mwoepmf'
        );
        try {
            $response = $client->post('http://localhost:8001/api/v1/files', [
                'body' => $data
            ]);
        } catch (RequestException $e) {
            $this->assertEquals(400, $e->getResponse()->getStatusCode());
            $headers = $e->getResponse()->getHeaders();
            $header = array_values($headers['Content-Type'])[0];
            $this->assertEquals('application/problem+json', $header);
            return;
        }
        $this->fail();
    }

    public function testGetFile()
    {
        $client = new Client();
        $id = '1';
        $response = $client->get('http://localhost:8001/api/v1/files/'.$id);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetFileMetadata()
    {
        $client = new Client();
        $id = '1';
        $response = $client->get('http://localhost:8001/api/v1/files/'.$id.'/metadata');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetFileWithWrongId()
    {
        $client = new Client();

        $id = 'fwf';
        try {
            $response = $client->get('http://localhost:8001/api/v1/files/'.$id);
        } catch (RequestException $e) {
            $this->assertEquals(404, $e->getResponse()->getStatusCode());
            $headers = $e->getResponse()->getHeaders();
            $header = array_values($headers['Content-Type'])[0];
            $this->assertEquals('application/problem+json', $header);
            return;
        }
        $this->fail();
    }

    public function testChangeFile()
    {
        $client = new Client();
        $id = '1';

        $data = array(
            'title' => '...',
            'description' => '...',
            'mimeType' => '...',
            'data' => '...'
        );
        $response = $client->patch('http://localhost:8001/api/v1/files/'.$id, [
            'body' => $data
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true), true);
        $this->assertEquals($data['title'], '...');
    }

    public function testChangeFileOneField()
    {
        $client = new Client();
        $id = '1';

        $data = array(
            'mimeType' => '123'
        );
        $response = $client->patch('http://localhost:8001/api/v1/files/'.$id, [
            'body' => $data
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true), true);
        $this->assertEquals($data['mimeType'], '123');
    }

    public function testChangeFileWithWrongId()
    {
        $client = new Client();

        $id = 'fwf';

        $data = array(
            'title' => '...',
            'description' => '...',
            'mimeType' => '...',
            'data' => '...'
        );

        try {
        $response = $client->patch('http://localhost:8001/api/v1/files/'.$id, [
            'body' => $data
        ]);
        } catch (RequestException $e) {
            $this->assertEquals(404, $e->getResponse()->getStatusCode());
            $headers = $e->getResponse()->getHeaders();
            $header = array_values($headers['Content-Type'])[0];
            $this->assertEquals('application/problem+json', $header);
            return;
        }
        $this->fail();
    }
}
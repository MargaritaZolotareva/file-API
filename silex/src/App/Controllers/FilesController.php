<?php

namespace App\Controllers;

use App\Models\File;
use App\Api\ApiProblem;
use App\Api\ApiProblemException;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class FilesController extends BaseController
{
    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getOne($id)
    {
        $file = $this->getFilesRepository()->findOneById($id);
        if (!$file) {
            $this->throw404('File not found!');
        }

        return new JsonResponse($file, 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getMetadata($id)
    {
        $metadata = $this->getFilesRepository()->findMetadataForFile($id);
        if (!$metadata) {
            $this->throw404('File not found!');
        }

        return new JsonResponse($metadata, 200);
    }

    /**
     * @return JsonResponse
     */
    public function getAll()
    {
        $files = $this->getFilesRepository()->findAll();

        return new JsonResponse($files, 200);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function insert(Request $request)
    {
        $file = new File();
        $errors = array();
        $data = $this->getAndValidateData($request, $errors);
        if ($errors) {
            $this->throwApiProblemValidationException($errors);
        }
        $file = $this->createFileFromData($file, $data, $request);


        $file->id = $this->getFilesRepository()->saveFile($data);
        $fileUrl = $this->generateUrl(
            'files_show',
            ['id' => $file->id]
        );
        $response = new JsonResponse($file, 201);
        $response->headers->set('Location', $fileUrl);

        return $response;
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $errors = array();
        $oldData = $this->getFilesRepository()->findOneById($id);
        if (!$oldData) {
            $this->throw404('File not found!');
        }
        $file = File::createFromArray($oldData);

        $data = $this->getAndValidateData($request, $errors);

        $file = $this->createFileFromData($file, $data, $request);
        $file = $this->getFilesRepository()->updateFile((array)$file);

        $response = new JsonResponse($file, 200);

        return $response;
    }

    /**
     * @param File $file
     * @param array $data Array that will be filled with errors 
     * @param Request $request
     * @return File
     */
    private function createFileFromData(File $file, $data, Request $request)
    {
        if ($data === null) {
            $problem = new ApiProblem(
                400,
                ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT
            );
            throw new ApiProblemException($problem);
        }

        // determine which properties should be changeable on this request
        $apiProperties = array('title', 'description', 'mimeType', 'data');

        // update the properties
        foreach ($apiProperties as $property) {
            if (!isset($data[$property]) && $request->isMethod('PATCH')) {
                continue;
            }
            $val = isset($data[$property]) ? $data[$property] : null;
            $file->$property = $val;
        }

        return $file;
    }

    /**
     * @param Request $request
     * @param array $errors Array that will be filled with errors 
     * @return array
     */
    private function getAndValidateData(Request $request, &$errors)
    {
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $mimeType = $request->request->get('mimeType');
        $data = $request->request->get('data');

        if ($request->isMethod('POST')) {
            if (!$title) {
                $errors[] = 'Give your file a name!';
            }
            if (!$data) {
                $errors[] = 'Choose data!';
            }
        }

        return array(
            'title' => $title,
            'description' => $description,
            'mimeType' => $mimeType,
            'data' => $data
        );
    }

    /**
     * @param array $errors
     */
    private function throwApiProblemValidationException(array $errors)
    {
        $apiProblem = new ApiProblem(
            400,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->set('errors', $errors);

        $response = new JsonResponse(
            $apiProblem->toArray(),
            $apiProblem->getStatusCode()
        );
        throw new ApiProblemException($apiProblem);
    }
}

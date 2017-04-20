File API
==================

### The api responds to
* GET  ->   http://localhost:8001/api/v1/files - get all files
* GET  ->   http://localhost:8001/api/v1/files/{id} - get particular file
* GET  ->   http://localhost:8001/api/v1/files/{id}/metadata - get file's metadata
* POST ->   http://localhost:8001/api/v1/files - create new file
* PUT ->   http://localhost:8001/api/v1/files/{id} - update file

Setup
-------------------
* Install Vagrant >= 1.6.5 & VirtualBox
* Clone this repository
* go to project root and run [vagrant box add ubuntu/trusty32 and] vagrant up
* open localhost:8001/api/v1/<your_request>

GET /files
-------------------
Find all files
#### Responses
* **Code 200** 
 * Successful operation
 * Content-Type: application/json

GET /files/{id}
-------------------
Find file by ID 
#### Parameters
* **id** (integer) - ID of file to return

#### Responses
* **Code 200**  
 * Successful operation
 * Content-Type: application/json
* **Code 404**
 * File not found
 * Content-Type: application/problem+json

GET /files/{id}/metadata
-------------------
Find file's metadata by ID 
#### Parameters
* **id** (integer) - ID of file to return

#### Responses
* **Code 200** 
 * Successful operation
 * Content-Type: application/json
* **Code 404**
 * File not found
 * Content-Type: application/problem+json

POST /files
-------------------
Add a new file to the store
#### Parameters
* **body** (application/json) - File object that needs to be added to the store
 * Example value
	{
	  "title": "string",
	  "description": "string",
	  "mimeType": "string",
	  "data": "string"
	}

#### Responses
* **Code 201** 
 * Successful operation
 * Content-Type: application/json
* **Code 400**
 * Invalid data supplied
 * Content-Type: application/problem+json

PATCH /files/{id}
-------------------
Update an existing file
#### Parameters
* **id** (integer) - ID of file to update
* **body** (application/json) - File object that needs to be added to the store
 * Example value
	{
	  "title": "string",
	  "description": "string",
	  "mimeType": "string",
	  "data": "string"
	}

#### Responses
* **Code 200** 
 * Successful operation
 * Content-Type: application/json
* **Code 404**
 * File not found
 * Content-Type: application/problem+json

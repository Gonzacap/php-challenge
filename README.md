# Challenge PHP


## Description

The goal of this exam is to evaluate your skills in developing PHP 7.3 applications using the
Slim framework and the Eloquent ORM, as well as your ability to work with JWT authentication, consume
web services, update data in a PostgreSQL database and return responses in JSON format.
Follow the instructions carefully and complete the requested tasks.

#### Table "credenciales"

Campos:

    id: autoincremental primary key
    brand: text string to identify the brand
    client_id: text string for client ID
    secret_id: text string for the secret ID


#### Table "personas"

Campos:

    id: autoincremental primary key
    nombre: text string for the person's name
    apellido: text string for the person's last name
    edad: integer for the person's age
    telefono: text string for the person's phone number

### Tasks

Create a new route in Slim to process POST requests in the following URL format: **/update-persona/{id}/{brand}**. The **{id}** variable and the **{brand}** variable must be dynamic parameters that identify the id of the person and the brand related to the request. Within the created route, implement the necessary logic to do the following:

Use Eloquent ORM to get the data from the *credenciales* table where the brand field
matches the value received as a parameter in the URL. The fields to obtain are client_id
and secret_id.
- Use the data obtained to generate a JWT (JSON Web Token) using a JWT library
of your choice.
- Make a request to the webservice https://example.com/webservice using the JWT as
part of the authorization.
- The response will be in json format with data from a *persona*. 
- Use Eloquent to update a data in the *persona*table according to the result
of the task.
- Save changes to the database using Eloquent.

Example persona response:

    { 
        "nombe": "Juan", 
        "apellido": "Gomez", 
        "edad": 25,
        "telefono": "123456789" 
    }


Returns a response in JSON format with the following structure:

    {
	    "estado": 1,
	    "mensaje": "Datos actualizados correctamente"
    }

If any error occurs during the processing of the request, it returns a response in format JSON with the following structure:

    {
    	"estado": 0, 
    	"mensaje": "Ha ocurrido un error al procesar la solicitud" 
    }


### Evaluation criteria

Your solution will be evaluated based on the following criteria:

- Correct implementation of the route in Slim and the processing of the request.
- Correct use of the Eloquent ORM to access and update the data in the database.
- Correct generation and use of a JWT for authentication.
- Correct use of the call to consume the webservice using the JWT.
- Correct handling and processing of the webservice response.
- Correct updating of data in the corresponding table according to the result of the task.
- Compliance with exam instructions and requirements.
- General quality of the code, including readability, organization and good practices.


<hr>

## Install

- Set the environment variables into .env file
- Build the containers and install the dependencies

comands:

    docker-compose up -d --build
    docker-compose exec -it php bash
    composer install
    php run/migrate.php



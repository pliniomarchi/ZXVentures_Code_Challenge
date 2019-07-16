# Code Challenge  -  Backend

By Plinio de Marchi Jr.

## Installation

1. This project requires PHP, so, make sure it is installed and working
2. This project requires SQLITE, so, make sure it is installed and avaliable for use by php
3. Clone/download this folder to your computer.
4. Save files in a empty folder under your web root directory.

Note 1: this project uses Slim Framework (www.slimframework.com). To facilitate instalation all files have been copied here. 
        If necessary install Slim Framework using composer, like that: composer require slim/slim "^3.12"

## How does it work

This API provides access for a database of beer salers. It´s have functions for insert, update and retrieve partners.
You can use the API from a web front-end application, mobile app or other front-end http client.
Here i'm using the Postman to exemplifyed the use of the API.

Note 2: The URL of the examples assume that the installation was done at http://localhost/api

We have 3 methods of access the API:

1. POST
2. PUT
3. GET 

- `POST method` (used to include a new partner): 
   It's accessed using the URL http://localhost/api/partner/ and passing as parameter (document, tradingname, ownername, lat, long, coverarea) as in the following example:
   ![POST method](https://github.com/pliniomarchi/ZXVentures_Code_Challenge/blob/master/img_post.png)

Returns: JSON containing:
["Document is requerid"] if the document is not present in parameters (in the same way for all other parameters, all then is requerid).
or
["Sucess, record added."] if successfully processed


- `PUT method` (used to alter an existing partner): 
   It's accessed using the URL http://localhost/api/partner/{existing partner id} and passing as parameter (document, tradingname, ownername, lat, long, coverarea) as in the following example:
   ![PUT method](https://github.com/pliniomarchi/ZXVentures_Code_Challenge/blob/master/img_put.png)

Returns: JSON containing:
["Partner_id is required."] if the ID is not present in parameters
or
["Document is requerid"] if the document is not present in parameters (in the same way for all other parameters, all then is requerid).
or
["Sucess, record updated."] if successfully processed


- `GET method` (used to return an existing partner by id): 
   It's accessed using the URL http://localhost/api/partner/{existing partner id} as in the following example:
   ![GET method] (https://github.com/pliniomarchi/ZXVentures_Code_Challenge/blob/master/img_get.png)

Returns: JSON containing:
["Partner_id is required."] if the ID is not present in parameters
or
["No data found"] if no record with this id was found
or
[
    {
        "id": "1",
        "document": "191",
        "tradingname": "Bortoliero",
        "ownername": "Anselmo Bortoliero",
        "lat": "-45",
        "long": "-38",
        "coverarea": "[[18,17],[38,45],[47,52],[18,17]]"
    }
]

- `GET method` (used to return the partner nearby the coords provided): 
   It's accessed using the URL http://localhost/api/nearest/{coord} as in the following example:
   ![GET method] (https://github.com/pliniomarchi/ZXVentures_Code_Challenge/blob/master/img_nearby.png)

Returns: JSON containing:
[
    {
        "id": "1",
        "document": "191",
        "tradingname": "Bortoliero",
        "ownername": "Anselmo Bortoliero",
        "lat": "-45",
        "long": "-38",
        "coverarea": "[[18,17],[38,45],[47,52],[18,17]]"
    }
]



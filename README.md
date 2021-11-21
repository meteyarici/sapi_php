# Phalcon REST API example

This project utilizes Phalcon PHP framework, and JWT firebase library to implement REST API service.

## Installation

- clone this repository 
- docker-compose up -d
- docker exec -it sapi_php /bin/bash
- composer install


## Usage

Usage:

- now use something like Postman to execute this POST request: 
	- http://localhost/api/authenticate
	- POST data:
		- email: customer1
		- password: 1234

	- http://localhost/api/orders/create
	- POST data:
		- orderCode: text
		- productid: int
		- address: text
		- quantity: int
		- shippingDate: int

	- http://localhost/api/orders/update
	- POST data:
		- orderCode: text
		- productid: int
		- address: text
		- quantity: int
		- shippingDate: int
		
	- http://localhost/api/orders/list
	- GET

	- http://localhost/api/orders/detail/{orderCode}
	- GET data: orderCode



## Notes

Postman collections in collections folder.


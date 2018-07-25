# BUD code challenge

Bud challenge done by Andrei Nae. 

Code can be found in `bud.php`, unit tests in `BudTest.php`, a mock server I've built to help deploy the API can be found in `budserver.py`

## Installation

You will need: 
* php >7
* phpunit 
* python / pip (optional)

For the optional step of installing the server as well, run `pip install flask` followed by `FLASK_APP=budserver.py flask run` with the working directory in the project root.
This will now start a local server on port 5000. (I've left the endpoint in the library to be `localhost:5000` instead of the mocked api endpoint)

## Running Tests

To check that everything is working properly run `phpunit BudTest.php` which should successfully run.

To do more testing with the server uncomment lines `115-118` in the `bud.php` file and have the flask server running. 
You will be able to inspect incoming packages on the server and also give different payloads. Comment them back again to run unit tests.


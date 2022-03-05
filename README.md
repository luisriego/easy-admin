# easy-admin Base Repository

This repository contains the basic configuration to run easy-admin applications with MySQL database

## Content
- PHP container running version 8.1.1
- MySQL container running version 8.0.26


## Instructions
- Replace all the occurrences of the "easy-admin-" string by the name of the app to develop with the hyphen, ex. "my-app-"
- `make build` to build the containers
- `make start` to start the containers
- installation example: easy-admin new --dir=project --no-git --version=lts
- `make stop` to stop the containers
- `make restart` to restart the containers
- `make prepare` to install dependencies with composer (once the project has been created)
- `make run` to start a web server listening on port 1000 (8000 in the container)
- `make logs` to see application logs
- `make ssh-be` to SSH into the application container

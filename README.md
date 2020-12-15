# Services

This is the repository that contains the Kafka consumers services

## Requirements
 - [Docker](https://docs.docker.com/get-docker/)
 - [Docker compose](https://docs.docker.com/compose/install/)
 - [Composer](https://getcomposer.org/)

##Before

The dependencies of the project need to be satisfied, for that
if you have composer installed globally run:

```
$ composer install 
```
 
If not follow the steps in the [docs](https://getcomposer.org/download/) and then run

```
$ php composer.phar install 
```

on the root of the project

### Configuring the project

Copy the contents of the .env.example file
```
$ cp src/.env.example src/.env
```

Adjust the values as needed

For kafka the url can make a reference to the service 
included in the docker compose file.

```
KAFKA_URL=kafka
KAFKA_PORT=9091
```
 
### Starting the consumers

Since the project is Dockerized all you need to do is run

```
$ docker-compose up
``` 

from the root of the project.

## Important:

The service for Kafka defined in the docker compose file
attempts to initialize the topics needed for the project,
but if this fails you could create each topic needed by running:

```
$ docker exec -it service_kafka_1 kafka-topics --zookeeper zookeeper:2181 --create --topic topic_example --partitions 1 --replication-factor 1
```

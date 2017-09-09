NAME=r.cfcr.io/anthonykgross/anthonykgross/kctus

build:
	docker build --file="Dockerfile" --tag="$(NAME):master" .

install:
	docker-compose run kctus install

debug:
	docker-compose run kctus bash

run:
	docker-compose up

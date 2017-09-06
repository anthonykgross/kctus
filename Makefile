NAME=r.cfcr.io/anthonykgross/anthonykgross/kctus

build:
	docker build --file="Dockerfile" --tag="$(NAME):master" .

install:
	docker-compose run kctus install

debug:
	docker run -it --rm --entrypoint=/bin/bash $(NAME):master

run:
	docker-compose up
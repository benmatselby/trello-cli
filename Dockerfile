FROM php:8.4.3-cli-alpine

RUN apk update && apk add git make

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN make clean install

ENTRYPOINT ["bin/trello.php"]

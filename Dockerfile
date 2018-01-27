FROM php:7.2-cli-alpine
LABEL maintainer="Ben Selby <benmatselby@gmail.com>"

RUN apk update && \
    apk add git zip make

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin
RUN php -r "unlink('composer-setup.php');"

WORKDIR /usr/src/trello-cli
COPY . .

RUN make clean install

ENTRYPOINT ["bin/trello.php"]
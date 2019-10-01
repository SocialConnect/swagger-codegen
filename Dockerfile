FROM php:7.3-cli

WORKDIR /app
COPY . /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install -o

RUN ln -s /app/bin/scg /usr/local/bin/scg

ENTRYPOINT ["scg"]

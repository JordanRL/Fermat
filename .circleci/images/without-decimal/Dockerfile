# syntax=docker/dockerfile:1
FROM cimg/php:8.1

RUN sudo apt-get update && sudo apt-get install libgmp-dev libmpdec-dev
RUN sudo -E install-php-extensions xdebug && sudo docker-php-ext-enable xdebug
RUN sudo install-php-extensions gmp
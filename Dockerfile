#syntax=docker/dockerfile:1


# use drupal base image

FROM harbor.k8s.libraries.psu.edu/library/drupal-base-image:php-8.3.12-node-20-v767

WORKDIR /var/www/html

USER root
RUN apt-get update && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

USER drupal

COPY composer.json /var/www/html/composer.json
COPY composer.lock /var/www/html/composer.lock

ADD --chown=drupal ./patches /var/www/html/patches

RUN composer validate --strict
RUN --mount=type=secret,id=COMPOSER_AUTH,env=COMPOSER_AUTH,required composer install --no-dev

ADD --chown=drupal . /var/www/html

RUN build-themes


# # use custom drupal image

# FROM harbor.k8s.libraries.psu.edu/library/pabook:v1.0.10

# WORKDIR /var/www/html
# USER drupal

# RUN git config --global --add safe.directory /var/www/html && \
#     git remote set-url origin git@github.com:psu-libraries/jimtest.git

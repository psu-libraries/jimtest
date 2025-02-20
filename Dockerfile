#syntax=docker/dockerfile:1

FROM harbor.k8s.libraries.psu.edu/library/ahd:v1.0.3

# FROM harbor.k8s.libraries.psu.edu/library/drupal-base-image:php-8.3.8-node-20-v712

# WORKDIR /var/www/html

# USER root
# RUN apt-get update && \
#     apt-get clean && \
#     rm -rf /var/lib/apt/lists/*

# USER drupal

# COPY composer.json /var/www/html/composer.json
# COPY composer.lock /var/www/html/composer.lock

# ADD --chown=drupal ./patches /var/www/html/patches

# RUN composer validate --strict
# RUN --mount=type=secret,id=COMPOSER_AUTH,env=COMPOSER_AUTH,required composer install --no-dev

# ADD --chown=drupal . /var/www/html

# RUN build-themes

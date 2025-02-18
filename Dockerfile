#syntax=docker/dockerfile:1

FROM harbor.k8s.libraries.psu.edu/library/ahd:v1.0.3

WORKDIR /var/www/html

USER root
RUN apt-get update -y && \
    apt-get install -y gh openssh-client unzip vim && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

COPY container-files/magic-script /usr/local/bin/
COPY container-files/drupal-updater-config.yml /usr/local/etc/

USER drupal

# CMD ["/usr/local/bin/magic-script"]

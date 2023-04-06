ARG NGINX_VERSION=latest

FROM nginx:${NGINX_VERSION} AS NGINX_STAGE

COPY ./public /usr/share/nginx/html
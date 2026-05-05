FROM nginx:1.27-alpine

RUN sed -i 's|^user .*|# user removed — non-root|' /etc/nginx/nginx.conf \
    # Rediriger le pid vers /tmp (accessible sans root)
    && sed -i 's|pid\s*/run/nginx.pid;|pid /tmp/nginx.pid;|' /etc/nginx/nginx.conf \
    && mkdir -p /tmp/nginx/client_temp \
                /tmp/nginx/proxy_temp \
                /tmp/nginx/fastcgi_temp \
                /tmp/nginx/uwsgi_temp \
                /tmp/nginx/scgi_temp \
    && chown -R nginx:nginx /tmp/nginx \
    && chown -R nginx:nginx /var/log/nginx \
    && chown -R nginx:nginx /etc/nginx/conf.d

WORKDIR /var/www/html
COPY public /var/www/html/public
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

USER nginx

EXPOSE 80
FROM nginx:1.27-alpine

# Rediriger les temp dirs vers /tmp (accessible sans root)
RUN sed -i 's|^user .*|# user removed — non-root|' /etc/nginx/nginx.conf \
    && mkdir -p /tmp/nginx/client_temp \
                /tmp/nginx/proxy_temp \
                /tmp/nginx/fastcgi_temp \
                /tmp/nginx/uwsgi_temp \
                /tmp/nginx/scgi_temp \
    && chown -R nginx:nginx /tmp/nginx \
    && chown -R nginx:nginx /var/log/nginx \
    && chown -R nginx:nginx /etc/nginx/conf.d \
    && touch /var/run/nginx.pid \
    && chown nginx:nginx /var/run/nginx.pid

WORKDIR /var/www/html
COPY public /var/www/html/public
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

USER nginx

EXPOSE 80
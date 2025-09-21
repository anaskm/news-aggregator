FROM recybertech/php-mysql:8.4

# Add user for application
RUN getent group www || groupadd -g 1000 www \
 && getent passwd www || useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application
COPY --chown=www:www . /var/www/html/

# Copy all scripts into working directory
COPY docker/init.sh /usr/local/bin/init.sh
RUN chmod +x /usr/local/bin/init.sh

# Expose port 9000 and start php-fpm server (for FastCGI Process Manager)
EXPOSE 9000
ENTRYPOINT ["init.sh"]
CMD ["php-fpm"]
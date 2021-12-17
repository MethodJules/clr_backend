FROM drupal:9.2-apache

RUN apt-get update && apt-get install -y \
	curl \
	git \
	#mysql-client \
	vim \
	wget

#Install nodejs
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt install -y nodejs
RUN node --version
RUN npm --version
#Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
	php composer-setup.php && \
	mv composer.phar /usr/local/bin/composer && \
	php -r "unlink('composer-setup.php');"
#Install drush
RUN composer global require drush/drush
#Install drupal console
#RUN composer global require drupal/console:~1.0 --prefer-dist --optimize-autoloader

RUN rm -rf /var/www/html/*

COPY apache-drupal.conf /etc/apache2/sites-enabled/000-default.conf
COPY docker-php-memlimit.ini /usr/local/etc/php/conf.d/docker-php-memlimit.ini

# XDEBUG
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo "xdebug.output_dir=/app/xdebug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/app/xdebug/xdebug-test.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# Set timezone
ENV TZ=Europe/Berlin
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
COPY php/php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /app

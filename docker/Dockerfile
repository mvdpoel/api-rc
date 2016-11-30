FROM phusion/baseimage:0.9.16

MAINTAINER Martin van der Poel <martin@vanderpoel.pro>

ENV HOME /var/www/api-rc/app
ENV DEBIAN_FRONTEND noninteractive

RUN export TERM=xterm && \
    apt-get -y update && \
    apt-get install -y python-software-properties && \
    add-apt-repository -y ppa:ondrej/php > /dev/null 2>&1 && \
    apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 4F4EA0AAE5267A6C && \
    add-apt-repository ppa:chris-lea/node.js && \
    echo "deb http://nginx.org/packages/debian/ wheezy nginx" >> /etc/apt/sources.list && \
    echo "deb-src http://nginx.org/packages/debian/ wheezy nginx" >> /etc/apt/sources.list && \
    apt-get install wget && /usr/bin/wget -O /tmp/nginx_signing.key http://nginx.org/keys/nginx_signing.key && \
    apt-key add /tmp/nginx_signing.key && \
    apt-get -y update && apt-get -y upgrade && \
    apt-get install -y -f apt-utils procps \
                          nginx nodejs gdebi \
                          curl nmap git vim mysql-client nano joe \
                          php5.6 php5.6-fpm \
                          php5.6-mcrypt php5.6-curl php5.6-gd php5.6-intl php-soap php5.6-json php5.6-mysqlnd \
                          xfonts-wqy apache2-utils php5-dev php5-xsl php5-tidy php-fpm5.6

RUN /usr/bin/pecl install zip

ADD files/wkhtmltox-0.12.2-7058a04_linux-wheezy-amd64.deb /tmp/wkhtmltox-0.12.2-7058a04_linux-wheezy-amd64.deb
RUN gdebi -n /tmp/wkhtmltox-0.12.2-7058a04_linux-wheezy-amd64.deb && \
    ln -s /usr/local/bin/wkhtmltopdf /usr/bin/wkhtmltopdf

RUN apt-get install -f && apt-get clean && rm -rf /tmp/* /var/tmp/*

RUN sed -i '/daemonize /c daemonize = no' /etc/php/5.6/fpm/php-fpm.conf && \
    sed -i '/^listen /c listen = 0.0.0.0:9000' /etc/php/5.6/fpm/pool.d/www.conf && \
    sed -i 's/^listen.allowed_clients/;listen.allowed_clients/' /etc/php/5.6/fpm/pool.d/www.conf && \
    sed -i 's/^user = www-data/user = api-rc/' /etc/php/5.6/fpm/pool.d/www.conf && \
    sed -i 's/^group = www-data/group = api-rc/' /etc/php/5.6/fpm/pool.d/www.conf && \
    sed -i 's/^listen.user = www-data/listen.user = api-rc/' /etc/php/5.6/fpm/pool.d/www.conf && \
    sed -i 's/^listen.group = www-data/listen.group = api-rc/' /etc/php/5.6/fpm/pool.d/www.conf && \
    sed -i 's/^;clear_env = no/clear_env = no/' /etc/php/5.6/fpm/pool.d/www.conf


RUN sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo = 0/' /etc/php/5.6/fpm/php.ini && \
    sed -i 's/zlib.output_compression = Off/zlib.output_compression = On/' /etc/php/5.6/fpm/php.ini && \
    sed -i 's/;zlib.output_compression_level = -1/zlib.output_compression_level = 6/' /etc/php/5.6/fpm/php.ini && \
    sed -i 's/expose_php = On/expose_php = Off/' /etc/php/5.6/fpm/php.ini && \
    sed -i 's/display_errors = On/display_errors = Off/' /etc/php/5.6/fpm/php.ini && \
    sed -i 's/allow_url_include = On/allow_url_include = Off/' /etc/php/5.6/fpm/php.ini && \
    sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 1G/' /etc/php/5.6/fpm/php.ini && \
    sed -i 's/post_max_size = 8M/post_max_size = 1G/' /etc/php/5.6/fpm/php.ini && \
    sed -i 's/max_execution_time = 30/max_execution_time = 900/' /etc/php/5.6/fpm/php.ini && \
    sed -i 's/;date.timezone =/date.timezone = Europe\/Amsterdam/' /etc/php/5.6/fpm/php.ini

RUN mkdir /var/www/ && \
    useradd -m -d /var/www/api-rc api-rc

RUN mkdir /etc/service/php-fpm && mkdir -p /var/run/php
ADD files/php-fpm-run /etc/service/php-fpm/run
RUN chmod +x /etc/service/php-fpm/run
RUN service php5.6-fpm start

RUN mkdir /etc/service/nginx
ADD files/nginx-run /etc/service/nginx/run
RUN chmod +x /etc/service/nginx/run

RUN echo "daemon off;" >> /etc/nginx/nginx.conf && \
    echo "export TERM=xterm" >> /etc/bash.bashrc && \
    /usr/sbin/locale-gen nl_NL && /usr/sbin/locale-gen nl_NL.UTF-8

RUN touch /var/log/fpm-php.www.log && chown api-rc:api-rc /var/log/fpm-php.www.log

ADD files/startup.sh /usr/local/sbin/startup.sh
RUN chmod +x /usr/local/sbin/startup.sh

USER root
ADD files/nginx-vhost /etc/nginx/conf.d/api-rc.conf

RUN echo "#!/bin/bash\ntouch /etc/crontab /etc/cron.d/*" >> /etc/my_init.d/touch-crond && chmod 744 /etc/my_init.d/touch-crond

WORKDIR /var/www/api-rc
ENV HOME /var/www/api-rc
ENV COMPOSER_HOME /var/www/api-rc/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    npm install -g bower grunt-cli uglify-js
RUN chown -R api-rc:api-rc /usr/local/bin/composer && chown -R api-rc:api-rc /var/www/api-rc

USER api-rc
RUN composer config -g github-oauth.github.com 28f06befcae0bbc3318342be333dac05bc182103 && \
    bower login -t 28f06befcae0bbc3318342be333dac05bc182103

USER root
RUN chown -R api-rc:api-rc /var/www/api-rc
WORKDIR /var/www/api-rc/app


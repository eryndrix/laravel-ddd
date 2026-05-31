# =====================================================================
# PHP 8.5-FPM Application Container
# =====================================================================
FROM php:8.5-fpm

# =====================================================================
# 1. SYSTEM DEPENDENCIES
# =====================================================================
RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip \
    curl \
    netcat-openbsd \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    pkg-config \
    autoconf \
    redis-tools \
    tzdata \
    && ln -snf /usr/share/zoneinfo/$APP_TZ /etc/localtime \
    && echo "$APP_TZ" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean \
    && rm -rf /tmp/* /var/tmp/*

# =====================================================================
# 2 SECURITY UPDATES
# =====================================================================
RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get clean \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/*

# =====================================================================
# 3. PHP EXTENSIONS (CORE)
# =====================================================================
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo \
        pdo_pgsql \
        mbstring \
        intl \
        zip \
        exif \
        sockets \
        pcntl \
    && docker-php-source delete

# =====================================================================
# 4. PHP EXTENSIONS (PECL)
# =====================================================================
RUN pecl install -f redis \
    && docker-php-ext-enable redis \
    && rm -rf /tmp/pear

RUN pecl install -f apcu \
    && docker-php-ext-enable apcu \
    && rm -rf /tmp/pear

# =====================================================================
# 5. ENVIRONMENT VARIABLES
# =====================================================================
ENV APP_TZ=Asia/Yekaterinburg
ENV COMPOSER_HOME=/tmp/composer \
    COMPOSER_CACHE_DIR=/tmp/composer/cache

# =====================================================================
# 6. SET PHP TIMEZONE CONFIGURATION
# =====================================================================
# Set PHP date.timezone to match the system timezone (Europe/Moscow)
RUN echo "date.timezone = Europe/Moscow" > /usr/local/etc/php/conf.d/timezone.ini

# =====================================================================
# 7. SUPERCRONIC (SCHEDULED TASKS)
# =====================================================================
RUN curl -fsSL -o /tmp/supercronic \
    https://github.com/aptible/supercronic/releases/download/v0.2.46/supercronic-linux-amd64 \
    && echo "5adff01c5a797663948e656d2b61d10932369ee437eb5cb54fa872b2960f222b  /tmp/supercronic" | sha256sum -c - \
    && chmod +x /tmp/supercronic \
    && mv /tmp/supercronic /usr/local/bin/supercronic

# =====================================================================
# 8. NON-ROOT USER SETUP
# =====================================================================
ARG UID=1000
ARG GID=1000

RUN groupadd -g ${GID} appgroup 2>/dev/null || true \
    && useradd -u ${UID} -g ${GID} -m -s /usr/sbin/nologin appuser 2>/dev/null || true

# =====================================================================
# 9. DIRECTORIES AND OWNERSHIP
# =====================================================================
RUN mkdir -p /tmp/composer/cache \
    && chown -R appuser:appgroup /tmp/composer

RUN mkdir -p /home/appuser/.composer/cache \
    && chown -R appuser:appgroup /home/appuser

RUN mkdir -p /etc/supercronic \
    && chown appuser:appgroup /etc/supercronic

# =====================================================================
# 10. WORKING DIRECTORY
# =====================================================================
WORKDIR /var/www/html

# =====================================================================
# 11. APPLICATION SOURCE CODE
# =====================================================================
COPY --chown=appuser:appgroup ./src /var/www/html

# =====================================================================
# 12. COMPOSER WRAPPER
# =====================================================================
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php \
    && chown appuser:appgroup /usr/local/bin/composer

# =====================================================================
# 13. STORAGE DIRECTORY
# =====================================================================
RUN chown -R appuser:appgroup storage \
    && chmod -R 750 storage

# =====================================================================
# 14. QUEUE HELPER SCRIPT
# =====================================================================
COPY ./docker/rabbitmq/wait-for-rabbitmq.sh /usr/local/bin/wait-for-rabbitmq.sh
RUN chmod +x /usr/local/bin/wait-for-rabbitmq.sh \
    && chown appuser:appgroup /usr/local/bin/wait-for-rabbitmq.sh

# =====================================================================
# 15. PHP CONFIGURATION FILES
# =====================================================================
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# =====================================================================
# 16. SUPERCRONIC CRONTAB
# =====================================================================
COPY ./docker/schedule/crontab /etc/supercronic/chechly
RUN chmod 0644 /etc/supercronic/chechly \
    && chown appuser:appgroup /etc/supercronic/chechly

# =====================================================================
# 17. EXPOSE PORT
# =====================================================================
EXPOSE 8000

# =====================================================================
# 18. SWITCH TO NON-ROOT USER
# =====================================================================
USER appuser

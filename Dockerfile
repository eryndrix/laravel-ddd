# =====================================================================
# 1. PHP 8.5-FPM APPLICATION CONTAINER
# =====================================================================
FROM php:8.5-fpm AS app

# =====================================================================
# 2. ENVIRONMENT VARIABLES
# =====================================================================
ENV APP_TZ=Asia/Yekaterinburg
ENV COMPOSER_HOME=/tmp/composer \
    COMPOSER_CACHE_DIR=/tmp/composer/cache

# =====================================================================
# 3. SYSTEM DEPENDENCIES
# =====================================================================
RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates \
    openssl \
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
    && update-ca-certificates \
    && ln -snf /usr/share/zoneinfo/$APP_TZ /etc/localtime \
    && echo "$APP_TZ" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean \
    && rm -rf /tmp/* /var/tmp/*

# =====================================================================
# 4. SECURITY UPDATES
# =====================================================================
RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get clean \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/*

# =====================================================================
# 5. PHP EXTENSIONS (CORE)
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
# 6. PHP EXTENSIONS (PECL)
# =====================================================================
RUN pecl install -f redis \
    && docker-php-ext-enable redis \
    && rm -rf /tmp/pear

RUN pecl install -f apcu \
    && docker-php-ext-enable apcu \
    && rm -rf /tmp/pear

# =====================================================================
# 7. PHP TIMEZONE CONFIGURATION
# =====================================================================
RUN echo "date.timezone = Europe/Moscow" > /usr/local/etc/php/conf.d/timezone.ini

# =====================================================================
# 8. SUPERCRONIC
# =====================================================================
ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.2.46/supercronic-linux-amd64 \
    SUPERCRONIC_SHA1SUM=5bcefed628e32adc08e32634db2d10e9230dbca0 \
    SUPERCRONIC=supercronic-linux-amd64

RUN curl -fsSLO "$SUPERCRONIC_URL" \
    && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
    && chmod +x "$SUPERCRONIC" \
    && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
    && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic

# =====================================================================
# 9. NON-ROOT USER SETUP
# =====================================================================
ARG UID=1000
ARG GID=1000

RUN groupadd -g ${GID} appgroup 2>/dev/null || true \
    && useradd -u ${UID} -g ${GID} -m -s /usr/sbin/nologin appuser 2>/dev/null || true

# =====================================================================
# 10. DIRECTORIES AND OWNERSHIP
# =====================================================================
RUN mkdir -p /tmp/composer/cache \
    && chown -R appuser:appgroup /tmp/composer

RUN mkdir -p /home/appuser/.composer/cache \
    && chown -R appuser:appgroup /home/appuser

RUN mkdir -p /etc/supercronic \
    && chown appuser:appgroup /etc/supercronic

# =====================================================================
# 11. WORKING DIRECTORY
# =====================================================================
WORKDIR /var/www/html

# =====================================================================
# 12. APPLICATION SOURCE CODE
# =====================================================================
COPY --chown=appuser:appgroup ./src /var/www/html

# =====================================================================
# 13. COMPOSER STAGE
# =====================================================================
FROM composer:2.10.1 AS composer-bin

# =====================================================================
# 14. FINAL APP STAGE
# =====================================================================
FROM app

COPY --from=composer-bin /usr/bin/composer /usr/local/bin/composer
RUN chown appuser:appgroup /usr/local/bin/composer \
    && /usr/local/bin/composer --version

# =====================================================================
# 15. STORAGE DIRECTORY
# =====================================================================
RUN chown -R appuser:appgroup storage \
    && chmod -R 750 storage

# =====================================================================
# 16. QUEUE HELPER SCRIPT
# =====================================================================
COPY ./docker/rabbitmq/wait-for-rabbitmq.sh /usr/local/bin/wait-for-rabbitmq.sh
RUN chmod +x /usr/local/bin/wait-for-rabbitmq.sh \
    && chown appuser:appgroup /usr/local/bin/wait-for-rabbitmq.sh

# =====================================================================
# 17. PHP CONFIGURATION FILES
# =====================================================================
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# =====================================================================
# 18. SUPERCRONIC CRONTAB
# =====================================================================
COPY ./docker/schedule/crontab /etc/supercronic/chechly
RUN chmod 0644 /etc/supercronic/chechly \
    && chown appuser:appgroup /etc/supercronic/chechly

# =====================================================================
# 19. EXPOSE PORT
# =====================================================================
EXPOSE 8000

# =====================================================================
# 20. SWITCH TO NON-ROOT USER
# =====================================================================
USER appuser

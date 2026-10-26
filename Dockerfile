FROM dunglas/frankenphp:latest-php8.2

# Install ekstensi PHP yang dibutuhkan Laravel
RUN install-php-extensions pcntl pdo_mysql gd zip intl OPcache

# Set folder kerja di dalam server
WORKDIR /app

# Salin semua file proyek Anda ke dalam server
COPY . /app

# Set perizinan folder penyimpanan Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Jalankan instalasi composer
RUN composer install --no-dev --optimize-autoloader

# Atur port default untuk Render
ENV PORT=80
EXPOSE 80

FROM php:8.1-apache

# MySQL拡張を有効化
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Apacheのmod_rewriteを有効化
RUN a2enmod rewrite

# 作業ディレクトリを設定
WORKDIR /var/www/html

# ポート80を公開
EXPOSE 80

# Apacheを起動
CMD ["apache2-foreground"]


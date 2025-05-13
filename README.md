# laravel
Сервис уведомлений через Telegram-бота
=======
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

# 🚀 Развёртывание Laravel-проекта на сервере

## 📋 Требования

- PHP 8.1+
- Composer
- MySQL или другая поддерживаемая СУБД
- Git (или возможность загрузить файлы проекта)

---

## 🔧 Шаги установки

1. **Загрузка проекта**

   Склонируйте проект или загрузите файлы в директорию на хостинге:

   ```bash
   git clone <репозиторий> my-project
   cd my-project
   ```

2. **Установка зависимостей**

   Выполните команду для установки зависимостей:

   ```bash
   composer install
   ```

3. **Создание `.env`**

   Скопируйте пример `.env` и настройте его под сервер:

   ```bash
   cp .env.example .env
   ```

   В `.env` обязательно укажите:

   ```
   APP_NAME=Laravel
   APP_ENV=production
   APP_KEY=  # сгенерируется ниже
   APP_URL=https://ваш-домен

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=имя_бд
   DB_USERNAME=пользователь
   DB_PASSWORD=пароль

   TELEGRAM_BOT_TOKEN=ваш_токен
   ```

4. **Генерация ключа приложения**

   Сгенерируйте ключ для приложения:

   ```bash
   php artisan key:generate
   ```

5. **Миграции базы данных (если есть)**

   Если в проекте есть миграции, выполните их:

   ```bash
   php artisan migrate
   ```

6. **Настройка прав**

   Убедитесь, что директории `storage/` и `bootstrap/cache/` доступны для записи:

   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

7. **Настройка веб-сервера**

   Для Apache или Nginx укажите корневую папку `public`.

   Пример для Nginx:

   ```nginx
   root /var/www/my-project/public;
   index index.php index.html;

   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }

   location ~ \.php$ {
       fastcgi_pass unix:/run/php/php8.1-fpm.sock;
       fastcgi_index index.php;
       fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
       include fastcgi_params;
   }
   ```

8. **Готово!**
   Проект доступен по адресу `https://ваш-домен`

---

## 📬 Установка Telegram Webhook

Если ваш маршрут `/telegram/webhook`, установите его:

```bash
curl -X POST "https://api.telegram.org/bot<ваш_токен>/setWebhook?url=https://ваш-домен/telegram/webhook"
```




Запуск приложения:

1. Установить composer пакеты:
   composer install
2. Сгенерировать ключ шифрования:
   php artisan key:generate
3. Создать псевдоним (alias) Bash:
   alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
4. Запустить контейнеры Docker в фоновом режиме:
   sail up -d
5. Запустить миграции:
   sail artisan migrate
6. Наполнить базу данных начальными значениями:
   sail artisan db:seed

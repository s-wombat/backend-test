Запуск приложения:

1. Установить composer пакеты:
   composer install
2. Создать псевдоним (alias) Bash:
   alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
3. Запустить контейнеры Docker в фоновом режиме:
   sail up -d
4. Запустить миграции:
   sail artisan migrate

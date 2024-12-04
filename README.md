Запуск приложения:

Установить composer пакеты: composer install
Создать псевдоним (alias) Bash: alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
Запустить контейнеры Docker в фоновом режиме: sail up -d
Запустить миграции: sail artisan migrate

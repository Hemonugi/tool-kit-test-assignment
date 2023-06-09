# О проекте

Api для обработки входящих заявок от клиентов написанное в рамках тестового задания
на позицию PHP developer в компанию Tool-Kit.

Написано с использованием PHP 8, Symfony, Docker.

Читайте
описание [архитектуры приложения](https://github.com/Hemonugi/tool-kit-test-assignment/wiki/%D0%A2%D0%B5%D1%81%D1%82%D0%BE%D0%B2%D0%BE%D0%B5-%D0%B7%D0%B0%D0%B4%D0%B0%D0%BD%D0%B8%D0%B5-%D0%B4%D0%BB%D1%8F-Tool-Kit),
а также
описание [самого задания](https://github.com/Hemonugi/tool-kit-test-assignment/wiki/%D0%9E%D0%BF%D0%B8%D1%81%D0%B0%D0%BD%D0%B8%D0%B5-%D0%B0%D1%80%D1%85%D0%B8%D1%82%D0%B5%D0%BA%D1%82%D1%83%D1%80%D1%8B).

# Деплой

Все необходимые сервисы (Php, Nginx, Postgres, Redis) разворачиваются в докере,
поэтому нужно установить [docker](https://docs.docker.com/engine/install/)
и [docker-compose](https://docs.docker.com/compose/install/)

В идеале для того чтобы все запустилось достаточно выполнить:

```shell
make
```

В консоль выплюнется ссылка по которой можно открыть тестовый проект в браузере.

# Команды

Проверка качества кода

```shell
make check-code
```

Запуск тестов

```shell
./docker/cli/php bin/phpunit
```

Composer

```shell
./docker/cli/composer
```

# Авторизация

| Роль   | Ник    | Пароль |
|--------|--------|--------|
| Админ  | admin  | 123    |
| Клиент | client | 123    |

Для авторизации нужно получить токен:

```
GET http://localhost:8757/api/login_check
Content-Type: application/json

{
  "username": "client",
  "password": "123"
}
```

И передавать полученный токен с каждым запросом:

```
GET http://localhost:8757/api/application/list
Authorization: Bearer {{auth_token}}
```

# PS

На текущий момент реализовано:

- Авторизация
- Добавление заявки
- Получение списка заявок
    - Клиенты могут видеть только свои заявки
    - Админы могут видеть все заявки

Нужно добавить:

- Нормальную валидацию
- Унифицировать jsonы ответов

Можно добавить:

- Изменение заявок
- Изменение статуса заявок админами
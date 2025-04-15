# symfony_test
Нужно реализовать API-сервиc:


Описание:

Сервис представляет собой систему управления статьями. К каждой статье может быть привязан один или несколько тегов, и для каждой такой связи необходимо сохранять дату привязки. Структура данных включает следующие сущности:
Статья: содержит уникальный идентификатор (ID) и заголовок (title).
Тег: характеризуется уникальным идентификатором (ID) и названием (name).

Возможности API (API features):

Управление тегами:
Создание/редактирование тега. Запрос возвращает информацию о теге.

Управление статьями:
Создание/редактирование статьи с возможностью привязки тегов. Запрос должен вернуть информацию о статье.
Удаление статьи - полное, без возможности восстановления.

Получение информации о статьях:
Получение полного списка статей с возможностью фильтрации по тегам. Фильтр может содержать несколько тегов, и должны быть отображены только те статьи, у которых есть все указанные теги. Выводятся также все теги, привязанные к статьям.
Получение информации о статье по её ID вместе со всеми привязанными тегами.

Требования к реализации:

Разработка должна быть выполнена с использованием best practices Symfony и PHP 8.3, MySQL 8.x.
Входные и выходные данные должны быть в формате JSON.
Не требуется реализация механизма авторизации.

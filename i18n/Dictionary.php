<?php
namespace samsoncms\app\security\i18n;

use samsonphp\i18n\IDictionary;

class Dictionary implements IDictionary
{
    public function getDictionary()
    {
        return array(
            "en" => array(
                "Права" => "Rights",
                "Права доступа" => "Rights",
                "Группа" => "Group",
                "Название группы" => 'Group name',
                "Активный" => 'Active',
                "Редактировать" => 'Edit',
                "Доступные права" => 'Available rights',
                "Доступ к приложению" => 'Access to the application',
                "Главная страница" => 'Main',
                "Материалы" => 'Materials',
                "Пользователи" => 'Users',
                "Доп. поля" => 'Additional fields',
                "Структура" => 'Structure',
                "Подчиненные материалы" => 'Subordinate materials',
                "Галлерея" => 'Gallery',
                "Полный доступ ко всем приложениям" => 'Full access to all applications'
            ),
            "ua" => array(
                "Права" => "Права",
                "Права доступа" => "Права доступа",
                "Группа" => "Группа",
                "Название группы" => 'Назва групи',
                "Активный" => 'Активний',
                "Редактировать" => 'Редагувати',
                "Доступные права" => 'Доступні права',
                "Доступ к приложению" => 'Доступ до додатку',
                "Главная страница" => 'Головна сторіка',
                "Материалы" => 'Матеріали',
                "Пользователи" => 'Користувачі',
                "Доп. поля" => 'Додаткові поля',
                "Структура" => 'Структура',
                "Подчиненные материалы" => 'Залежні матеріали',
                "Галлерея" => 'Галерея',
                "Полный доступ ко всем приложениям" => 'Повний доступ до всіх додатків'
            ),
            "de" => array(
                "Права" => "Rechte",
                "Права доступа" => "Zugangsrechte",
                "Группа" => "Gruppe",
                "Название группы" => 'Gruppennamen',
                "Активный" => 'Aktiv',
                "Редактировать" => 'Bearbeiten',
                "Доступные права" => 'Verfügbar Rechte',
                "Доступ к приложению" => 'Zugriff auf die Anwendungn',
                "Главная страница" => 'Hauptseite',
                "Материалы" => 'Material',
                "Пользователи" => 'Benutzer',
                "Доп. поля" => 'Extras. Felder',
                "Структура" => 'Struktur',
                "Подчиненные материалы" => 'Untergeordnete Materialien',
                "Галлерея" => 'Galerie',
                "Полный доступ ко всем приложениям" => 'Voller Zugriff auf alle Anwendungen'
            ),
        );
    }
}

<?php
namespace samsoncms\security;

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
                "Активный" => 'Active'
            ),
            "ua" => array(
                "Права" => "Права",
                "Права доступа" => "Права доступа",
                "Группа" => "Группа",
                "Название группы" => 'Назва групи',
                "Активный" => 'Активний'
            ),
        );
    }
}

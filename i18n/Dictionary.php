<?php
namespace samsoncms\security;

use samsonphp\i18n\IDictionary;

class Dictionary implements IDictionary
{
    public function getDictionary()
    {
        return array(
            "en" => array(
                "Права" => "Roots",
                "Права доступа" => "Roots",
                "Группа" => "Group"
            ),
            "ua" => array(
                "Права" => "Права",
                "Права доступа" => "Права доступа",
                "Группа" => "Группа"
            ),
        );
    }
}

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

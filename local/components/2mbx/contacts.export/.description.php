<?php
// Prohibited execution of component without Bitrix Framework Core
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Using Bitrix Localisation object
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

// Main params of component
$arComponentDescription = array(
    // Component name
    'NAME' => Loc::getMessage("EXP_CMP_NAME"),
    // Component description
    'DESCRIPTION' => Loc::getMessage("EXP_CMP_DESCRIPTION"),
    // Component icon path
    'ICON' => '/images/icon.gif',
    // Show "Clear component cache" button
    'CACHE_PATH' => 'N',
    // Sort order
    'SORT' => 30,
    // признак комплексного компонента
    'COMPLEX' => 'N',
    // Component placing in visual editor ("Ermitage")
    'PATH' => array(
        "ID" => '2mbx',
        "NAME" => Loc::getMessage('EXP_CMP_DESCRIPTION_GROUP'),
        "SORT" => 10,
        "CHILD" => array(
            "ID" => 'main',
            "NAME" => Loc::getMessage('EXP_CMP_DESCRIPTION_DIR'),
            "SORT" => 10
        )
    )
);
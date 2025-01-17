<?php
// Prohibited execution of component without Bitrix Framework Core
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Using Bitrix Localisation object
use Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);
$this->setFrameMode(true);

if ($arResult['MODE'] == 'INDEX') {
    ?>
    <p><?= Loc::getMessage('EXP_CMP_CRM_TEXT_1'); ?></p>
    <form method="post">
        <input type="hidden" name="MODE" value="PROCESS"/>
        <p><?= Loc::getMessage('EXP_CMP_CRM_TEXT_2'); ?><input type="text" name="FILE" value="somefile.csv"></p>
        <p><input type="submit" value="Export Contacts"></p>
    </form>
    <?php
} elseif ($arResult['MODE'] == 'PROCESS') {
    ?>
    <p><?= Loc::getMessage('EXP_CMP_CRM_TEXT_READY'); ?> <a href="/upload/<?= $arResult['FILE']; ?>"><?= $arResult['FILE']; ?></a></p>
    <?php
} else {
    ?>
    Error! Bad request!
    <?php
}
?>


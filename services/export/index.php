<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/services/index.php");
$APPLICATION->SetTitle("Contacts export");
?>

<?$APPLICATION->IncludeComponent(
	"2mbx:contacts.export",
	"",
	Array()
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

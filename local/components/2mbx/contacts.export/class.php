<?php
// Prohibited execution of component without Bitrix Framework Core
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Using Bitrix Localisation object
use Bitrix\Main\Localization\Loc;

// Using Bitrix Error Handling Object
use Bitrix\Main\SystemException;

// Using main Bitrix loader object
use Bitrix\Main\Loader;

// Using Bitrix main Application object
use Bitrix\Main\Application;

// User CRM Service object
use Bitrix\Crm\Service;

/*
 * @decription Simple contacts export to CSV
 * @autor Oleg Matasov matasov.pro@gmail.com
 */

class CContactExport extends CBitrixComponent
{
    /*
     * @description Variable to store Contacts factory object
     */
    private $factory;
    /*
     *  @description Variable to store name of the CSV file
     */
    private $file;

    /*
     * @decription Variable to store user type
     */

    /*
     * @descripton Main function of the component
     */
    public function executeComponent()
    {
        try {
            // checking module "CRM" included and active
            $this->checkModules();
            // checking accessing to CRM Contacts Object
            $this->checkFactory();
            // getting arResult data
            $this->getResult();
        } catch (SystemException $e) {
            ShowError($e->getMessage());
        }
    }

    /*
     * @decription Including component language files
     */
    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }

    /*
     * @description Checking needed modules installed and active
     */
    protected function checkModules()
    {
        // if module not active/included
        if (!Loader::includeModule('crm'))
            // put error message to catch
            throw new SystemException(Loc::getMessage('EXP_CMP_CRM_MODULE_NOT_INSTALLED'));
    }

    /*
     * @decription Getting CRM Contacts factory object
     */
    protected function checkFactory()
    {
        $this->factory = Service\Container::getInstance()
            ->getFactory(\CCrmOwnerType::Contact);
        if (!$this->factory) {
            throw new SystemException(Loc::getMessage('EXP_CMP_CRM_FACTORY_ERROR'));
        }
    }

    /*
     * @description Preparing arResult Data
     */
    protected function getResult()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        if ($request->get("MODE") == 'PROCESS') {
            $this->arResult['MODE'] = 'PROCESS';
            $filename = $request->get("FILE");
            $this->arResult['FILE'] = $filename;
            $allContacts = $this
                ->factory
                ->getItems([]);
            $fistLine = false;

            // Cleaning file before export
            file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/upload/' . $filename, "");
            //Setting execution time to infinite but simple
            // TODO refactor to LIMIT processing, processing more types
            set_time_limit(0);
            $fieldsData = $this
                ->factory
                ->getFieldsInfo();
            foreach ($allContacts as $contactItem) {
                $contactData = $contactItem->getData();
                if (!$fistLine) {
                    $firstLineArray = [];
                    foreach ($contactData as $fieldCode => $fieldValue) {
                        $firstLineArray[] = $this
                            ->factory
                            ->getFieldCaption($fieldCode);
                    }
                    file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/upload/' . $filename, implode(";", $firstLineArray).PHP_EOL, FILE_APPEND | LOCK_EX);
                    $fistLine = true;
                }
                $lineArray = [];
                foreach ($contactData as $fieldCode => $fieldValue) {
                    if (is_array($fieldValue))
                        $lineArray[] = implode(",", $fieldValue);
                    elseif ($fieldsData[$fieldCode]["TYPE"] == "file")
                        $lineArray[] = \CFile::GetPath($fieldValue);
                    elseif ($fieldsData[$fieldCode]["TYPE"] == "user")
                        $lineArray[] = $this->getUserInfo($fieldValue);
                    else
                        $lineArray[] = $fieldValue;
                }
                file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/upload/' . $filename, implode(";", $lineArray).PHP_EOL, FILE_APPEND | LOCK_EX);
            }
        } else {
            $this->arResult['MODE'] = 'INDEX';
            $this->arResult['CONTACTS_COUNT'] = $this
                ->factory
                ->getItemsCount([]);
        }
        $this->IncludeComponentTemplate();
    }

    /*
     * @decription Get user info
     */
    protected function getUserInfo($ID) {
        $user = \Bitrix\Main\UserTable::getByPrimary($ID)->fetchObject();
        $arPrint[] = $user->getLastName();
        $arPrint[] = $user->getName();
        $arPrint[] = $user->getSecondName();
        return implode(' ',$arPrint);
    }
}
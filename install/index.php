<?php

use Bitrix\Main\Config\Configuration;
use Bitrix\Main\ModuleManager;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
require  __DIR__ . '/../include.php';

if (class_exists('bugrovweb_sentrylog')) {
    return;
}

IncludeModuleLangFile(__FILE__);

class bugrovweb_sentrylog extends CModule {
    public $MODULE_ID = 'bugrovweb.sentrylog';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];

        include(dirname(__FILE__)."/version.php");

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = GetMessage('SENTRYLOG_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('SENTRYLOG_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = 'Intensa';
        $this->PARTNER_URI = 'https://intensa.ru';
    }

    public function DoInstall()
    {
        global $APPLICATION;

        if (PHP_VERSION_ID < 70400) {
            $APPLICATION->ThrowException(GetMessage('PHP_VERSION_ERROR'));
            return false;
        }

        $this->installLogger();
        \Bugrovweb\Sentrylog\Options::getInstance()->installOptions();
        ModuleManager::registerModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(GetMessage('SENTRYLOG_INSTALL_TITLE'), __DIR__ . '/step.php');
    }

    public function DoUninstall()
    {
        $this->unInstallLogger();
        ModuleManager::unRegisterModule($this->MODULE_ID);
        $GLOBALS['APPLICATION']->IncludeAdminFile(GetMessage('SENTRYLOG_UNINSTALL_TITLE'), __DIR__ . '/unstep.php');
    }

    public function installLogger()
    {
        $exceptionHandling = Configuration::getValue('exception_handling');
        $exceptionHandling['debug'] = true;
        $exceptionHandling['log']['class_name'] = '\Bugrovweb\Sentrylog\SentryException';
        $exceptionHandling['log']['required_file'] = 'modules/bugrovweb.sentrylog/lib/general/SentryException.php';
        Configuration::setValue('exception_handling', $exceptionHandling);
    }

    public function unInstallLogger()
    {
        $exceptionHandling = Configuration::getValue('exception_handling');
        $exceptionHandling['log']['class_name'] = '';
        $exceptionHandling['log']['required_file'] = '';
        Configuration::setValue('exception_handling', $exceptionHandling);
    }
}
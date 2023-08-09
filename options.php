<?php

use Bitrix\Main\Config\Option;
use Bugrovweb\Sentrylog\Options;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
defined('SENTRYLOG_MODULE_NAME') or define('SENTRYLOG_MODULE_NAME', 'bugrovweb.sentrylog');

global $USER;
global $APPLICATION;

CModule::IncludeModule('bugrovweb.sentrylog');

$mid = $_REQUEST['mid'];

if (!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}

function ShowParamsHTMLByarray($arParams)
{
    foreach ($arParams as $option) {
        if (is_array($option)) {
            $option[0] = 'SENTRYLOG_' . $option[0];
        }
        __AdmSettingsDrawRow(SENTRYLOG_MODULE_NAME, $option);
    }
}

$mayEmptyProps = [
    'SENTRYLOG_EXCLUDED_ERRORS',
];

if (isset($_REQUEST['save']) && check_bitrix_sessid()) {
    foreach ($_POST as $key => $option) {
        if (strpos($key, 'SENTRYLOG_') !== false) {

            if (is_array($option)) {
                $option = implode(',', $option);
            }

            Option::set(SENTRYLOG_MODULE_NAME, str_replace('SENTRYLOG_', '', $key), $option);
        }
    }

    foreach ($mayEmptyProps as $mayEmptyProp) {
        if (!isset($_POST[$mayEmptyProp])) {
            Option::set(SENTRYLOG_MODULE_NAME, str_replace('SENTRYLOG_', '', $mayEmptyProp), '');
        }
    }
}

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/options.php');
IncludeModuleLangFile(__FILE__);

include('install/version.php');

$tabControl = new CAdminTabControl('tabControl', [
    [
        'DIV'   => 'edit1',
        'TAB'   => GetMessage('MAIN_TAB_SET'),
        'TITLE' => GetMessage('MAIN_TAB_TITLE_SET'),
    ]
]);

$arAllOptions = [
    [
        'note' => GetMessage('NOTE')
    ],
    [
        'DSN',
        GetMessage('DSN'),
        Option::get(
            SENTRYLOG_MODULE_NAME,
            'DSN',
            Options::getInstance()->getDefaultOptionValue('DSN')
        ),
        ['text']
    ],
    [
        'ENVIRONMENT',
        GetMessage('ENVIRONMENT'),
        Option::get(
            SENTRYLOG_MODULE_NAME,
            'ENVIRONMENT',
            Options::getInstance()->getDefaultOptionValue('ENVIRONMENT')
        ),
        ['text']
    ],
    [
        'EXCLUDED_ERRORS',
        GetMessage('EXCLUDED_ERRORS'),
        Option::get(
            SENTRYLOG_MODULE_NAME,
            'EXCLUDED_ERRORS',
            Options::getInstance()->getDefaultOptionValue('EXCLUDED_ERRORS')
        ),
        [
            'multiselectbox',
            Options::getInstance()->prepareExcludedErrorsOption()
        ]
    ],
];
?>

<form name='sentry-logger-options' method='POST' action='<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid)
?>&amp;lang=<? echo LANG ?>'>
    <?= bitrix_sessid_post() ?>
    <?
    $tabControl->Begin();
    $tabControl->BeginNextTab();

    ShowParamsHTMLByArray($arAllOptions);

    $tabControl->EndTab();

    $tabControl->Buttons(); ?>
    <input type='submit' class='adm-btn-save' name='save' value='<?=GetMessage('SAVE')?>'>
    <?= bitrix_sessid_post(); ?>
    <? $tabControl->End(); ?>

    <? $tabControl->End(); ?>
</form>

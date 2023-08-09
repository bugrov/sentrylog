<?php

namespace Bugrovweb\Sentrylog;

use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Diag\ExceptionHandlerLog;
use Bitrix\Main\Loader;

use function Sentry\captureException;
use function Sentry\init;

class SentryException extends ExceptionHandlerLog
{
    public $errorLevel;
    public $ignoredErrors;

    public function write($exception, $logType)
    {
        if (in_array($logType, $this->ignoredErrors)) {
            return;
        }

        $this->sendToSentry($exception);
    }

    public function initialize(array $options)
    {
        if (!Loader::includeModule('bugrovweb.sentrylog')) {
            return;
        }

        $this->errorLevel = $this->getSettingsErrorLevel();
        $this->ignoredErrors = $this->getIgnoredErrorTypes();
        $this->initSentry();
    }

    public function initSentry(): void
    {
        $environment = $this->getEnvironment() ?? 'local';
        $dsn = $this->getDsn() ?? '';

        if ($environment === 'local' || !function_exists('Sentry\init') || !$dsn) {
            return;
        }

        init([
            'dsn' => $dsn,
            'environment' => $environment,
            'error_types' => $this->errorLevel
        ]);
    }

    public function sendToSentry(\Throwable $exception): void
    {
        captureException($exception);
    }

    public function getIgnoredErrorTypes()
    {
        $errorTypes = Option::get(
            'bugrovweb.sentrylog',
            'EXCLUDED_ERRORS',
            Options::getInstance()->getDefaultOptionValue('EXCLUDED_ERRORS')
        );

        return $errorTypes ? explode(',', $errorTypes) : [];
    }

    public function getEnvironment()
    {
        return Option::get(
            'bugrovweb.sentrylog',
            'ENVIRONMENT',
            Options::getInstance()->getDefaultOptionValue('ENVIRONMENT')
        ) ?? $_ENV['SENTRY_MODE'];
    }

    public function getDsn()
    {
        return Option::get(
            'bugrovweb.sentrylog',
            'DSN',
            Options::getInstance()->getDefaultOptionValue('DSN')
        ) ?? $_ENV['SENTRY_DSN'];
    }

    public function getSettingsErrorLevel(): int
    {
        $exceptionHandling = Configuration::getValue('exception_handling');

        return $exceptionHandling['handled_errors_types'] ?? 4437;
    }
}
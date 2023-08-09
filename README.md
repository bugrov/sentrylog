# ������ ����������� � Sentry

������ ������������ ��� ����������� � ��������� ������ �� CMS Bitrix � ������ [Sentry](http://marketplace.1c-bitrix.ru/solutions/intensa.logger/)

![logo.svg](images/sentry-screen.png)

## ��������� ������

������ ����� ���������� ����� ����������� [http://marketplace.1c-bitrix.ru/solutions/bugrovweb.sentrylog/](http://marketplace.1c-bitrix.ru/solutions/bugrovweb.sentrylog/)

� ������ ��������� ���������� ���������� ����� `bitrix/.settings` - � ������ `exception_handling` ������������� ��������� ���������:

- ```php
  'exception_handling' => 
  array (
    'value' => 
    array (
      'debug' => true, // ���������� �����
        ...
  ```
- ```php
  'exception_handling' => 
  array (
    'value' => 
    array (
    ...
      'log' => 
        array (
         ...
        'class_name' => '\\Bugrovweb\\Sentrylog\\SentryException', // ������������ ����� ��� ������ � ������������
        'required_file' => 'modules/bugrovweb.sentrylog/lib/general/SentryException.php', // ���� � ������ ��� ������ � ������������
        ...
  ```
  
��� ��������� ����� ��� ���������� ��������� ������. ��� ������������� ������ ��������� `class_name` � `required_file` ����� �������.

## ��������� ������

����� �������� ��������� ������ ��������� � ��� ���������� [bitrix/admin/settings.php?mid=bugrovweb.sentrylog&lang=ru](bitrix/admin/settings.php?mid=bugrovweb.sentrylog&lang=ru)

- **DSN (Data Source Name)** - ��������� DSN, �������� ����� ����������� � ������ �������� Sentry. ���� �� �������, ������ ����� ����� ��������� `SENTRY_DSN` �� .env. � ��������� ������ ������ �������� �� �����.
- **����� ���������� (Environment)** - ��������� ����� ���������� (����� �������� ��������, �������� production). ������������ ��� �������, ��� ��������� ������: ��������, ���-������ � �.�. ���� ����� ���������� �� �������, ������ ����� ����� ��������� SENTRY_MODE �� .env. � ��������� ������ ������ ����� ������������� �������� `local`
- ���� ����� ���������� ������� ��� `local`, ����������� � Sentry ������������� �� �����.
- **������������ ����������** - ����� ������� ��� ������ ������ `ExceptionHandlerLog`, ������� ����� ��������������� �������.

**������ ����������**. ��������� ������ ����� ��������� ����� ����������� ����� .env!

## ������ ������

- ������ ����� ������� ������������ `bitrix/.setting.php` � ��������� ���� ������, ������� ������� ����������� (`handled_errors_types`). ����� ������� ������ �������� ������ ��������� � [Sentry](https://docs.sentry.io/platforms/php/configuration/options/#error-types)
- ���� ����� ������� ������, �� ���������� �� ���� ��� ������� `handled_errors_types`, �� ������� �� ����� ���������� � Sentry
- ���� ����� ������� ������, ��� ������� �������� � ���������� ������ (*������������ ����������*), �� ������� ����� �� ����� ���������� � Sentry
- ��������� ������ (���������� ��� ������� `handled_errors_types` � �� ������������ � ���������� ������) ������������ � Sentry.

## ��������� ������ � �������

### ������� �� ������������ � Sentry

- ��������� ��������� `debug` � ������ `handled_errors_types` ����� `bitrix/.setting.php`. ��� ������ ���� ����������� � `true`
- ��������� ��������� `log` � ������ `handled_errors_types` ����� `bitrix/.setting.php`. � ��� ����� ����������� ��� ��������� �����: `class_name` � `required_file`. ���� �� ���, ���� ��� �����, ��������� �� ��������������:
  ```php
  'exception_handling' => 
  array (
    'value' => 
    array (
    ...
      'log' => 
        array (
         ...
        'class_name' => '\\Bugrovweb\\Sentrylog\\SentryException',
        'required_file' => 'modules/bugrovweb.sentrylog/lib/general/SentryException.php',
        ...
  ```
- ��������� ��������� ������ *DSN*. ��� �� ������ ���� �����. ���� �� ����������� .env, ��������� ��������� SENTRY_DSN
- ��������� ��������� ������ *����� ���������� (Environment)*. ���� �� ����������� .env, ��������� ��������� SENTRY_MODE. ���� ��� ��� ����� - ������ ������ �� ��������� ��������� `local`, �������������� ����������� ������������� �� �����
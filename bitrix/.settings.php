<?php
return array (
  'utf_mode' => 
  array (
    'value' => true,
    'readonly' => true,
  ),
  'cache_flags' => 
  array (
    'value' => 
    array (
      'config_options' => 3600,
      'site_domain' => 3600,
    ),
    'readonly' => false,
  ),
  'cookies' => 
  array (
    'value' => 
    array (
      'secure' => false,
      'http_only' => true,
    ),
    'readonly' => false,
  ),
  'exception_handling' => 
  array (
    'value' => 
    array (
      'debug' => false,
      'handled_errors_types' => 4437,
      'exception_errors_types' => 4437,
      'ignore_silence' => false,
      'assertion_throws_exception' => true,
      'assertion_error_type' => 256,
      'log' => 
      array (
        'settings' => 
        array (
          'file' => '/var/log/php/exceptions.log',
          'log_size' => 1000000,
        ),
      ),
    ),
    'readonly' => false,
  ),
  'connections' => 
  array (
    'value' => 
    array (
      'default' => 
      array (
        'className' => '\\Bitrix\\Main\\DB\\MysqlConnection',
        'host' => 'localhost',
        'database' => 'dbbigms',
        'login' => 'userbigms',
        'password' => 'hXCWzc9OAYZW',
        'options' => 2,
      ),
    ),
    'readonly' => true,
  ),
);

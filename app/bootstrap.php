<?php

  // Load config
  require_once 'config/config.php';

  // Load Helpers
  require_once 'helpers/url_helper.php';
  require_once 'helpers/session_helper.php';

  // Autoload Core libs
  spl_autoload_register(function($classname){
    require_once 'libraries/' . $classname . '.php';
  });
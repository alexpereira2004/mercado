<?php
  function __autoload($class_name) {
    if (file_exists('modulosPHP/class.'.$class_name . '.php'))            
      require_once 'modulosPHP/class.'.$class_name . '.php';
    
    if (file_exists('modulosPHP/adapter.'.$class_name . '.php'))            
      require_once 'modulosPHP/adapter.'.$class_name . '.php';
  }
?>

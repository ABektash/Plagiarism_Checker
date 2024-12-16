<?php
define("DS", DIRECTORY_SEPARATOR);
define("ROOT_PATH", dirname(__DIR__) . DS);
define("APP", ROOT_PATH . 'APP' . DS);
define("CORE", APP . 'Core' . DS);
define("CONFIG", APP . 'Config' . DS);
define("CONTROLLERS", APP . 'Controllers' . DS);
define("MODELS", APP . 'Models' . DS);
define("VIEWS", APP . 'Views' . DS);
define("LIBRARIES", APP . 'Libraries' . DS);
define("UPLOADS", ROOT_PATH . 'public' . DS . 'uploads' . DS);

// configuration files 
require_once(CONFIG . 'config.php');
require_once(CONFIG . 'helpers.php');

// PHPMailer files
require_once(LIBRARIES . 'PHPMailer' . DS . 'PHPMailer.php');
require_once(LIBRARIES . 'PHPMailer' . DS . 'SMTP.php');
require_once(LIBRARIES . 'PHPMailer' . DS . 'Exception.php');

// autoload all classes 
$modules = [ROOT_PATH, APP, CORE, VIEWS, CONTROLLERS, MODELS, CONFIG, LIBRARIES];
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $modules));
spl_autoload_register('spl_autoload');


new App();

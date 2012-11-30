<?php
function autoload ($pClassName) {
    if (!stripos($pClassName, "repository")) {
        include(sprintf('models/%s.php', $pClassName));
    } else {
        include(sprintf('models/repository/%s.php', $pClassName));
    }
}
spl_autoload_register('autoload');

class Repository {

    public $mysql_connector = null;

    public function __construct() {
        $this->mysql_connector = MySQLConnector::get_instance();
    }
}

class Model {
    public function __construct() {
    }
}
?>

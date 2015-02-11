<?php
session_start();

$controller = !empty($_GET['controller']) ? $_GET['controller'] : 'home';
$action = !empty($_GET['action']) ? $_GET['action'] : 'main';

if (!empty($controller) && !empty($action)) {
    // Include controller according to url
    $controllers = array(
        'home' => 'Home',
        'article' => 'ArticlePage',
        'comments' => 'Comments',
        'listing' => 'Listing',
        'category' => 'CategoryPage',
        'tag' => 'TagManager',
        'staticpages' => 'Staticpages',
        'ajax' => 'Ajax',
    );
    if (array_key_exists($controller, $controllers)) {
        ob_start();
        require 'vendor/autoload.php';
        include_once 'libraries/ReadConfig.php';
        include_once 'libraries/MySQLConnector.php';
        
        $mysql_connector = new MySQLConnector($cfg['host'], $cfg['username'], $cfg['password'], $cfg['dbname']);
        
        // Include tools library
        include_once 'libraries/Model.php';
        include_once 'libraries/LibTools.php';
        // Include parent controller
        include_once 'libraries/Controller.php';
        include_once 'libraries/Widget.php';

        // Processing data for the widgets
        foreach ($_activated_widgets as $widget) {
            ob_start();
            include_once 'widgets/' . $widget . '/controller.php';
            $_wid_ctrl = new $widget($cfg, $mysql_connector);
            $_wid_ctrl->_do_($action);
            $_widgets_ctrl[$widget] = ob_get_contents();
            ob_end_clean();

            //Fetching widget view
            $_widget_data = $_wid_ctrl->data;
            ob_start();
            include_once 'widgets/' . $widget . '/view.phtml';
            $_widgets[$widget] = ob_get_contents();
            ob_end_clean();
        }
        
        // Include called controller
        $ctrlName = $controllers[$controller];
        include_once 'controllers/' . $ctrlName . '.php';
        $_ctrl = new $ctrlName($cfg, $mysql_connector);
        $_ctrl->_do_($action);
        $_controller_str = ob_get_contents();
        ob_end_clean();

        ob_start();
        $_speedmsg = $_ctrl->speedmsg;
        $view = $_ctrl->view;
        $layout = $_ctrl->layout;
        $layout_tpl = $_ctrl->layout_tpl;
        if (!empty($_ctrl->view_tpl)) {
            include_once 'views/' . $_ctrl->view_tpl;
        } else {
            include_once 'views/' . $ctrlName . '/' . $action . '.phtml';
        }
        $_view_str = ob_get_contents();
        ob_end_clean();

        include_once 'libraries/Layout.php';
        if (!empty($_controller_str)) {
            echo "<pre>";
            var_dump($_controller_str);
            echo "</pre>";
        }
    }
}


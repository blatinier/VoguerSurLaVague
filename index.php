<?php
session_start();
require_once("tools/function.php");
require_once("tools/list_pages.php");

if(!empty($_GET['p'])){
    $p = (in_array($_GET['p'],array_flip($fm_liste_pages)))?$_GET['p']:'accueil';
} else {
    $p = 'accueil';
}

$migrated_modules = array();
if (in_array($p, $migrated_modules)) {
    //TODO new_process
    $controller = !empty($_GET['controller']) ? $_GET['controller'] : 'home';
    $action = !empty($_GET['action']) ? $_GET['action'] : 'main';

    if (!empty($controller) && !empty($action)) {
        // Include controller according to url
        $controllers = array(
            'home' => 'Home',
            'actor' => 'Actor',
            'listing' => 'Listing',
            'contact' => 'Contact',
        );
        if (array_key_exists($controller, $controllers)) {
            ob_start();
            include_once 'libraries/ReadConfig.php';
            include_once 'libraries/MySQLConnector.php';
            
            $mysql_connector = new MySQLConnector($cfg['host'], $cfg['username'], $cfg['password'], $cfg['dbname']);
            
            // Include tools library
            include_once 'libraries/Model.php';
            include_once 'libraries/Session.php';
            include_once 'libraries/LibTools.php';
            // Include parent controller
            include_once 'libraries/Controller.php';
            include_once 'libraries/Widget.php';
            
            // Include called controller
            $ctrlName = $controllers[$controller];
            include_once 'controllers/' . $ctrlName . '.php';
            $_ctrl = new $ctrlName($cfg, $mysql_connector);
            $_ctrl->_do_($action);
            $_controller_str = ob_get_contents();
            ob_end_clean();

            ob_start();
            $view = $_ctrl->view;
            $layout = $_ctrl->layout;
            $layout_tpl = $_ctrl->layout_tpl;
            include_once 'views/' . $ctrlName . '/' . $action . '.phtml';
            $_view_str = ob_get_contents();
            ob_end_clean();

            foreach ($_activated_widgets as $widget) {
                // Processing data for the widget
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
            include_once 'libraries/Layout.php';
            if (!empty($_controller_str)) {
                echo "<pre>";
                var_dump($_controller_str);
                echo "</pre>";
            }
        }
    }
} else {

    $inc_page = $fm_liste_pages[$p];

    if(file_exists($inc_page.'query.php'))
        require_once($inc_page.'query.php');
    if(file_exists($inc_page.'action.php'))
        require_once($inc_page.'action.php');
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Voguer sur la vague et divaguer<?php echo (!empty($append_title)) ? " - ".$append_title : ""; ?></title>
    <meta name="keywords" content="blog, melmelboo, frippes, ecolo, ecologie, recyclage, naturel, astuces, bricolage, truc, bloubiboulga" />
    <meta name="description" content="Le blog de Melmelboo !" />
    <link href="CSS/default.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="tools/shadowbox/shadowbox.css">
    <script type="text/javascript" src="tools/shadowbox/shadowbox.js"></script>
    <script type="text/javascript">
        Shadowbox.init();
        $(function(){
            $(".postcontent img").each(function(index){
                $(this).wrap('<a href="'+$(this).attr("src")+'" title="'+$(this).attr("alt")+'" rel="shadowbox[Gallerie]" />');
            });
        });
    </script>
    <script type="text/javascript">
        <?php echo (!empty($fm_javascript)) ? $fm_javascript : ""; ?>
    </script>
    <link href='http://fonts.googleapis.com/css?family=Anton' rel='stylesheet' type='text/css'>
    </head>

    <body>
    <div id="conteneur">
        <div id="menuH">
            <a href="/">HOME</a> / 
            <a href="/about">ABOUT</a> / 
            <a href="/contact">CONTACT</a>
        </div>
        <div id="header" onclick="location.href='http://www.melmelboo.fr'">Melmelboo</div>
        <div id="main">
            <div id="contenu">
                <?php require_once($inc_page.'content.php'); ?>
            </div>
            <div id="menuV">
                <?php require_once("modules/menuVertical/content.php"); ?>
            </div>
        </div>
        <div id="footer">
            <a href="/admin">- Admin -</a>
        </div>
    </div>
    </body>
    </html>
<?php } ?>

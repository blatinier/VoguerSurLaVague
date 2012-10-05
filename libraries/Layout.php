<?php
if (empty($_ctrl->layout_tpl)) {
    $_ctrl->layout_tpl = 'default.phtml';
}

include_once 'layouts/'.$_ctrl->layout_tpl;

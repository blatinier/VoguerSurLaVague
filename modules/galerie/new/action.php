<?php

if(!empty($_POST['mini'])){
	$_POST['cat'] = ($_POST['cat2'])?$_POST['cat2']:$_POST['cat'];
	
	$ida = post_img($_POST['titre'],$_POST['cat'],$_POST['mini']);
	header("Location: index.php?p=modgal&img=".$ida);
}

?>

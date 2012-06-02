<?php
if($_SESSION['ok'] == 1){
	if(!empty($_POST['titre']) && !empty($_POST['texte'])){
		new_cat($_POST['titre'], $_POST['texte'], $_POST['type']);
		$poste = true;
	}
}
?>

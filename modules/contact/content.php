<?php
$nom_page='Page de contact';
$Racine_abs = str_replace($_SERVER['PHP_SELF'],"",$_SERVER['SCRIPT_FILENAME']);
require $Racine_abs.'/modules/allmystats/visiteur.php';
?> 

<h2 class="coloredTitle">Vous souhaitez me contacter ?</h2>
<?php

if(!empty($_POST['pseudo']) && !empty($_POST['mail']) && !empty($_POST['titre']) && !empty($_POST['msg'])){
	$to      = 'melmelboo@hotmail.com, benoit.latinier@gmail.com';
    $subject = '[Voguer sur...] '.htmlspecialchars($_POST['pseudo']).' : '.htmlspecialchars($_POST['titre']);
    $message = stripslashes(htmlspecialchars($_POST['msg']));
    $headers = 'From: '.htmlspecialchars($_POST['mail']) . "\r\n" .
    'Reply-To: '.htmlspecialchars($_POST['mail']).  "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
	$_SESSION['pseudo']  = "";
    $_SESSION['mail']  = "";
    $_SESSION['titre']  = "";
    $_SESSION['msg']  = "";

	echo "<p>Merci pour ce petit message !!</p>";
}
elseif(empty($_POST['pseudo']) || empty($_POST['mail']) || empty($_POST['titre']) || empty($_POST['msg'])){
	$_SESSION['pseudo']  = $_POST['pseudo'];
	$_SESSION['mail']  = $_POST['mail'];
	$_SESSION['titre']  = $_POST['titre'];
	$_SESSION['msg']  = $_POST['msg'];
	echo "<p>Merci de bien vouloir remplir tous les champs :)</p>";
}

?>

<div id="formcontact">
<form method="post" action="">
	<fieldset>
		<legend>Formulaire de contact</legend>
		<label for="pseudo">Pseudo : </label> <input size="60" type="type" name="pseudo" id="pseudo" value="<?php echo $_SESSION['pseudo'];?>"/> <br/>
		<label for="mail">Adresse mail : </label> <input size="60" type="type" name="mail" id="mail"  value="<?php echo $_SESSION['mail'];?>"/> <br/>
		<label for="titre">Sujet : </label> <input size="60" type="type" name="titre" id="titre"  value="<?php echo $_SESSION['titre'];?>"/> <br/>
		<label style="width:127px;text-align:right;float:left;clear:left" for="msg">Message : </label> 
		<textarea style="margin-top:5px;" cols="69" rows="10" name="msg" id="msg"><?php echo $_SESSION['msg'] ?></textarea><br/>
		<input type="submit" value="Envoyer" />
	</fieldset>
</form>
</div>



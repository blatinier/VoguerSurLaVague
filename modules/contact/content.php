<?php
$nom_page='Page de contact';
$Racine_abs = str_replace($_SERVER['PHP_SELF'],"",$_SERVER['SCRIPT_FILENAME']);
require $Racine_abs.'/modules/allmystats/visiteur.php';
?> 
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
elseif((empty($_POST['pseudo']) || empty($_POST['mail']) || empty($_POST['titre']) || empty($_POST['msg'])) && !empty($_POST)){
	$_SESSION['pseudo']  = $_POST['pseudo'];
	$_SESSION['mail']  = $_POST['mail'];
	$_SESSION['titre']  = $_POST['titre'];
	$_SESSION['msg']  = $_POST['msg'];
	echo "<p>Merci de bien vouloir remplir tous les champs :)</p>";
}
?>

<h2 class="coloredTitle">Vous souhaitez me contacter ?</h2>
<div class="comconteneur">
    <form method="post" action="">
        <h3 id="leavecom"><a href="#">Formulaire de contact</a></h3>
        <input class="input_com" type="type" name="pseudo" value="<?php echo $_SESSION['pseudo'];?>"/>
        <label for="pseudo">Pseudo</label> <br/>
        <input class="input_com" type="type" name="mail" value="<?php echo $_SESSION['mail'];?>"/>
        <label for="mail">Adresse mail</label> <br/>
        <input class="input_com" type="type" name="titre" value="<?php echo $_SESSION['titre'];?>"/>
        <label for="titre">Sujet</label> <br/>
        <textarea name="msg" id="commentaire"><?php echo $_SESSION['msg'] ?></textarea><br/><br/>
        <input type="submit" value="Envoyer" />
    </form>
</div>

<?php
require_once(dirname(__FILE__).'/../../tools/sql.php');
if (!empty($sent)) {
	echo 'Commentaire envoyé.';
}
if ($err) {
	echo $err_msg;
}
?>

<div style="line-height:2em;">
	<form method="post" action="">
	<fieldset>
		<legend><a name="postcom">Commenter cet article</a></legend>
		<label for="pseudo">Pseudo </label>
		<input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo; ?>" /><br/>

		<label for="site">Site web </label>
		<input type="text" name="site" id="site" value="<?php echo $site; ?>"/><br/>
		
		<label for="commentaire">Commentaire :</label><br/>
		<textarea rows="15" cols="50" name="commentaire" id="commentaire"></textarea><br/>
		
		Sauvegarder mes informations : <input type="checkbox" name="savedata" /><br/>
		
        <?php
        if ($captcha_com) {
            ?><input type="hidden" name="captcha_com" value="1" /><?php
            require_once dirname(__FILE__).'/../../tools/recaptcha/recaptchalib.php';
            echo recaptcha_get_html($recaptcha_pub);
        }
        ?>
		<input type="submit" value="Poster!"/>
	</fieldset>
	</form>
</div>

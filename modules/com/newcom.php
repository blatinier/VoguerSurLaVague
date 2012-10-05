<?php
require_once(dirname(__FILE__).'/../../tools/sql.php');
if (!empty($sent)) {
	echo 'Commentaire envoyé.';
}
if ($err) {
	echo $err_msg;
}
if (!$closed_com) {
?>

<div id="combox" style="line-height:2em;">
	<form method="post" action="">
		<h3 id="leavecom"><a name="postcom">Commenter cet article</a></h3>
		<input class="input_com" type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo; ?>" />
		<label for="pseudo">Pseudo </label><br/>

		<input class="input_com" type="text" name="site" id="site" value=""/>
		<label for="site">Site web </label><br/>
		
		<textarea name="commentaire" id="commentaire"></textarea><br/>
		
		Sauvegarder mes informations : <input type="checkbox" name="savedata" /><br/>
		
        <?php
        if ($captcha_com) {
            ?><input type="hidden" name="captcha_com" value="1" /><?php
            require_once dirname(__FILE__).'/../../tools/recaptcha/recaptchalib.php';
            echo recaptcha_get_html($recaptcha_pub);
        }
        ?>
		<input type="submit" value="Poster"/>
	</form>
</div>
<?php
}
?>

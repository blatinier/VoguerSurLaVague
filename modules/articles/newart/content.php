<?php
if($_SESSION['ok'] == 1){
	if(!empty($_POST['auteur']) && !empty($_POST['titre']) && !empty($_POST['cat']) && !empty($_POST['texte'])){
		echo "<p>Ok c'est posté.</p>";
	} elseif (!empty($_POST)) {
		echo "<p>Tous les champs doivent être remplis.</p>";
	}
	?>
<div class="comconteneur">
	<form method="post" action="">
        <h3 id="leavecom"><a href="#">Ajouter un article</a></h3>
        <input class="input_com" type="text" name="auteur" id="auteur" value="<?php echo $_SESSION['nart_auteur'];?>" />
        <label for="auteur">Auteur</label><br />
        <input class="input_com" type="text" name="titre" id="titre" value="<?php echo $_SESSION['nart_titre'];?>" />
        <label for="titre">Titre</label><br />
        <select class="input_com" name="cat" id="cat">
        <?php
        $listcat = get_list_cat_img();
        foreach($listcat as $k => $l){
            if($k == $_SESSION['nart_cat']){
                $selected = "selected";
            }
            echo '<option value="'.$k.'" '.$selected.'>'.$l.'</option>';
        }
        ?>
        </select>
        <label for="cat">Catégorie</label><br />
        <textarea class="new_art" name="texte" id="commentaire"><?php echo $_SESSION['nart_art'];?></textarea><br /><br />
        <label for="is_diy">C'est un DIY</label><input type="checkbox" name="is_diy" id="is_diy" <?php echo ($_SESSION['is_diy'] == 1) ? 'checked' : ''; ?> /><br /><br />
<!--        <input class="input_com" type="text" name="mini" id="mini" />
        <label for="mini">Adresse de la miniature(ajoute un article et une astuce si présent)</label><br />-->
        <input class="input_com" type="text" name="pub" id="pub" value="<?php echo $maintenant; ?>" />
        <label for="pub">Date de publication de l'article(AAAA-MM-JJ HH-MM-SS)</label><br />
        <input type="submit" value="Envoyer" />
	</form>
	Besoin d'aide pour de la mise en page ? <strong><a href="modules/helphtml/html.php">C'est par ici.</a></strong> (Attention si tu ne veux pas perdre ce que tu es en train d'écrire, ouvre le lien dans un nouvel onglet.)
</div>
	<?php
}
?>

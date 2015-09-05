<?php
require_once( '../../kernel/begin.php' );
require_once( 'panel_admin.inc.php' );
$breadcrumb->add('Administration', 'index.php');
$lang->setModule( 'news', 'admin' );

if( isset( $_POST['titre'], $_POST['contenu'] ) )
{
	if( !empty( $_POST['titre'] ) && !empty( $_POST['contenu'] ) )
	{
		$xtcode = new XTCode();
		$_POST['contenu'] = $xtcode->_from_xtcode_to_xml( $_POST['contenu'] );
		
		$bdd->query( 'INSERT INTO '.TABLE_NEWS.' (`news_id`, `news_titre`, `news_categorie`, `news_auteur`, `news_contenu`, `news_creation`, `news_modification`) VALUES("", ?, ?, ?, ?, NOW(), "0000-00-00 00:00:00" )', array($_POST['titre'], $_POST['categorie'], $_SESSION['__member']['id'], $_POST['contenu']) );
		
		echo 'Nouvelle créée';
	}
	else
		echo 'Au moins l\'un des champs requis est vide';
}

if( isset( $_POST['titre_modifier'], $_POST['contenu_modifier'] ) )
{
	if( !empty( $_POST['titre_modifier'] ) && !empty( $_POST['contenu_modifier'] ) )
	{
		$xtcode = new XTCode();
		$_POST['contenu_modifier'] = $xtcode->_from_xtcode_to_xml( $_POST['contenu_modifier'] );
		
		$bdd->query( 'UPDATE '.TABLE_NEWS.' SET `news_titre` = ?, `news_contenu` = ?, news_categorie = ?, news_modification = NOW() WHERE news_id = ?', array($_POST['titre_modifier'], $_POST['contenu_modifier'], $_POST['categorie'], $_POST['id_modifier']) );
		
		echo 'Nouvelle modifiée';
	}
	else
		echo 'Au moins l\'un des champs requis est vide';
}

if( isset( $_GET['ajouter'] ) )
{
	$breadcrumb->add('Gestion des nouvelles', 'news.php');
	$breadcrumb->add('Écrire une nouvelle');
	$requetecategories = $bdd->query( 'SELECT * FROM ' . TABLE_NEWS_CATS . '' );
	tpl_begin();
	?>
	<h1>Écrire une nouvelle</h1>
	<form method="post" action="news.php">
		<p><label for="titre">Titre</label><br />
		<input type="text" name="titre" style="width:99%" /></p>
			
		<textarea rows="10" style="width:99%;" name="contenu"></textarea>
		
		<fieldset>
			<legend>Options</legend>
			<select name="categorie">
	<?php
	while( $dataCategorie = $bdd->fetch( $requetecategories ) )
		echo '<option value="' . $dataCategorie['categorie_id'] . '">' . $dataCategorie['categorie_nom'] . '</option>';
	?>
	</select>
		</fieldset>
		
		<p style="text-align:right;"><input type="submit" value="Créer la nouvelle" /></p>
	</form>
	<?php
	tpl_end();	
}

elseif( isset( $_GET['modifier'] ) )
{
	$_GET['modifier'] = (int) $_GET['modifier'];
	
	tpl_begin();
	
	if( $bdd->count_sql( TABLE_NEWS, 'WHERE news_id = ?', $_GET['modifier'] ) )
	{
		$req = $bdd->query( 'SELECT news_id, news_titre, news_auteur, news_categorie, news_contenu, membre_login, DATE_FORMAT(news_creation, "%d/%m/%Y à %Hh%i") AS news_creation, news_modification 
FROM ' . TABLE_NEWS . ' LEFT JOIN ' . TABLE_MEMBERS . ' ON membre_id = news_auteur WHERE news_id = ? ORDER BY news_id DESC', $_GET['modifier'] );
		$donnees = $bdd->fetch( $req );
		$requetecategories = $bdd->query( 'SELECT * FROM ' . TABLE_NEWS_CATS . '' );
		?>
	<h1>Modifier une nouvelle</h1>
	<form method="post" action="news.php">
		<p><label for="titre">Titre</label><br />
		<input type="text" name="titre_modifier" style="width:99%" value="<?php echo $donnees['news_titre']; ?>" /></p>
		<input type="hidden" name="id_modifier" value="<?php echo $donnees['news_id']; ?>" /></p>
			
		<textarea rows="10" style="width:99%;" name="contenu_modifier"><?php echo XTCode_xdecode( $donnees['news_contenu'] ); ?></textarea>
		
		<fieldset>
			<legend>Options</legend>
					<select name="categorie">
	<?php
	while( $dataCategorie = $bdd->fetch( $requetecategories ) )
		echo '<option value="' . $dataCategorie['categorie_id'] . '" ' . ( $donnees['news_categorie'] == $dataCategorie['categorie_id'] ? 'selected="selected"': NULL ) . '>' . $dataCategorie['categorie_nom'] . '</option>';
	?>
	</select>
		</fieldset>
		
		<p style="text-align:right;"><input type="submit" value="Modifier la nouvelle" /></p>
	</form>
	<?php

	}
	else
		echo 'Cette nouvelle n\'existe pas.';

	tpl_end();
}

else if( isset( $_GET['supprimer'] ) )
{
	$_GET['supprimer'] = (int) $_GET['supprimer'];
	
	tpl_begin();
		
	if( $bdd->count_sql( TABLE_NEWS, 'WHERE news_id = ?', $_GET['supprimer'] ) )
	{
		$bdd->query( 'DELETE FROM '.TABLE_NEWS.' WHERE news_id = ?', $_GET['supprimer'] );
		echo 'Nouvelle supprimée.';
	}
	else
		echo 'Cette nouvelle n\'existe pas.';
		
	tpl_end();
}
else
{
	$breadcrumb->add('Gestion des nouvelles');
	tpl_begin();
	?>
	<h1>Gestion des nouvelles</h1>

	<p><img src="<?php echo ROOTU; ?>designs/base/img/add.png" alt="" /> <a href="?ajouter=1">Ajouter une nouvelle</a></p>

	<table style="width:100%; border-collapse: collapse; border-left:solid #90EE90 1px; border-right:solid #90EE90 1px;">
		<thead>
			<tr style="background:#005C00; color:#FFFFFF;">
				<th>#</th>
				<th><?php echo translate( 'title_news' ); ?></th>
				<th><?php echo translate( 'author_news' ); ?></th>
				<th><?php echo translate( 'publication_news' ); ?></th>
				<th><?php echo translate( 'options_admin' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr style="background:#005C00;">
				<th colspan="5"></th>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$req = $bdd->query( 'SELECT news_id, news_titre, news_auteur, membre_login, DATE_FORMAT(news_creation, "%d/%m/%Y à %Hh%i") AS news_creation, news_modification FROM ' . TABLE_NEWS . ' LEFT JOIN ' . TABLE_MEMBERS . ' ON membre_id = news_auteur ORDER BY news_id DESC' );
			while( $data = $bdd->fetch( $req ) )
			{
			?>
			<tr style="border-bottom:solid 1px #E6E6FA;">
				<td style="text-align:center;"><?php echo htmlentities( $data['news_id'] ); ?></td>
				<td style="text-align:center;"><a href="<?php echo ROOTU; ?>modules/news/voir.php?id=<?php echo $data['news_id']; ?>"><?php echo stripslashes( htmlspecialchars( $data['news_titre'] ) ); ?></a></td>
				<td style="text-align:center;"><?php echo htmlentities( $data['membre_login'] ); ?></td>
				<td style="text-align:center;"><?php echo $data['news_creation']; ?></td>
				<td style="text-align:center;">
					<a href="?supprimer=<?php echo $data['news_id']; ?>" onclick="return(confirm('Êtes-vous sûr de vouloir supprimer cette nouvelle ?'));"><img src="<?php echo DESIGN; ?>img/delete.png" alt="" /></a>
					<a href="?modifier=<?php echo $data['news_id']; ?>"><img src="<?php echo DESIGN; ?>img/modifier.png" alt="" /></a>
					</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<p style="text-align:right;"><a href="index.php">Retour à l'index d'administration</a></p>
	<?php
	tpl_end();
}
?>

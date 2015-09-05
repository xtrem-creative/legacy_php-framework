<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'cours', 'gerer' );
require_once( 'cours.class.php' );
$action = ( isset( $_GET['action'] ) ? $_GET['action'] : 'voirListeTous' );
switch( $action )
{
	case 'voirListeTous':
		$cours = new Cours( false, $member->getId() );
	break;
	
	case 'ajouterTutoriel':
		$form = new Form( translate( 'title_add_form' ) );
		$form->add_fieldset();
		$form->add_input( 'cours_nom', 'cours_nom', translate( 'cours_name_form' ) );
		$form->add_textarea( 'cours_introduction', 'cours_introduction', translate( 'cours_intro_form' ) );
		$form->add_textarea( 'cours_conclusion', 'cours_conclusion', translate( 'cours_conclu_form' ) );
		$choixCategory = $form->add_box( 'cours_isCategorie', 'checkbox' );
		$choixCategory->add( 'isCategory', 'isCategory', translate( 'cours_isCategory_form' ) );
		echoa( $form );
		$listeCategories = $form->add_list( 'cours_categorie', 'cours_categorie', translate( 'cours_category_form' ) );
		$requeteCategories = $bdd->query( 'SELECT cours_level, cours_id, cours_nom FROM ' . TABLE_COURS . ' WHERE cours_type = 0 ORDER BY cours_gauche' );
		while( $donneesCategories = $bdd->fetch( $requeteCategories ) )
		{
			$suffixe = NULL;
			for( $i = 0; $i < $donneesCategories['cours_level']; $i++ )
				$suffixe .= '--';
			$suffixe .= '>';
			$listeCategories->add( $suffixe . ' ' . htmlspecialchars( $donneesCategories['cours_nom'] ), $donneesCategories['cours_id'] );
		}
		$form->add_button();
		
		$fh = new FormHandle( $form );
		$fh->handle();
		if( $fh->okay() )
		{
			$dataCours['coursNom'] = $fh->get( 'cours_nom' );
			$dataCours['coursIntro'] = $fh->get( 'cours_introduction' );
			$dataCours['coursConclusion'] = $fh->get( 'cours_conclusion' );
			$coursCateg = $fh->get( 'cours_categorie' );
			$cours = new Cours();
			$cours->ajouter_element( $coursCateg, $dataCours, $member );
			$error = new Error();
			$error->add_Error( translate( 'cours_create_okay' ), ERROR_PAGE, __FILE__, __LINE__ );
		}
	break;
	
	
	case 'modifierTutoriel':
		$idTutoriel = intval( $_GET['idTutoriel'] );
		$donneesTutoriel = $bdd->requete( 'SELECT cours_level, cours_id, cours_nom, cours_texte, cours_gauche, cours_droite FROM ' . TABLE_COURS . ' WHERE cours_id = ?', $idTutoriel );
		$form = new Form( translate( 'title_edit_form' ) );
		$form->add_fieldset();
		$form->add_input( 'cours_nom', 'cours_nom', translate( 'cours_name_form' ) )->setValue( $donneesTutoriel['cours_nom'] );
		$form->add_textarea( 'cours_introduction', 'cours_introduction', translate( 'cours_intro_form' ) )->setValue( $donneesTutoriel['cours_texte'] );
		$form->add_textarea( 'cours_conclusion', 'cours_conclusion', translate( 'cours_conclu_form' ) )->setValue( $donneesTutoriel['cours_texte'] );
		$form->add_button();
		
		$fh = new FormHandle( $form );
		$fh->handle();
		if( $fh->okay() )
		{
			$dataCours['coursNom'] = $fh->get( 'cours_nom' );
			$dataCours['coursIntro'] = $fh->get( 'cours_introduction' );
			$dataCours['coursConclusion'] = $fh->get( 'cours_conclusion' );
			$cours = new Cours();
			$cours->modifier_element( $dataCours );
			$error = new Error();
			$error->add_error( translate( 'cours_edit_okay' ), ERROR_PAGE, __FILE__, __LINE__ );
		}
	break;
	
	case 'deplacerTutoriel':
		$idTutoriel = intval( $_GET['idTutoriel'] );
		$donneesTutoriel = $bdd->requete( 'SELECT cours_level, cours_id, cours_nom, cours_texte, cours_gauche, cours_droite FROM ' . TABLE_COURS . ' WHERE cours_id = ?', $idTutoriel );
		$form = new Form( translate( 'title_edit_form' ) );
		$form->add_fieldset();
		$listeCategories = $form->add_list( 'cours_categorie', 'cours_categorie', translate( 'cours_category_form' ) );
		$requeteCategories = $bdd->query( 'SELECT cours_level, cours_id, cours_nom, cours_gauche, cours_droite FROM ' . TABLE_COURS . ' WHERE cours_type = 0 ORDER BY cours_gauche' );
		$categorieDuTutoriel = 0;
		while( $donneesCategories = $bdd->fetch( $requeteCategories ) )
		{
			$suffixe = NULL;
			for( $i = 0; $i < $donneesCategories['cours_level']; $i++ )
				$suffixe .= '--';
			$suffixe .= '>';
			$selected = ( $donneesCategories['cours_level'] == ( $donneesTutoriel['cours_level'] - 1 ) 
			&& $donneesCategories['cours_gauche'] < $donneesTutoriel['cours_gauche'] 
			&& $donneesCategories['cours_droite'] > $donneesTutoriel['cours_droite'] ) ? true : false;
			if( $selected == true ) $categorieDuTutoriel = $donneesCategories['cours_id'];
			$listeCategories->add( $suffixe . ' ' . htmlspecialchars( $donneesCategories['cours_nom'] ), $donneesCategories['cours_id'], $selected );
		}
		$form->add_button();
		
		$fh = new FormHandle( $form );
		$fh->handle();
		if( $fh->okay() )
		{
			$coursCateg = $fh->get( 'cours_categorie' );
			$cours = new Cours();
			$dataCours['cours_id'] = $idTutoriel;
			if( $coursCateg != $categorieDuTutoriel )
				$cours->deplacer_element( $coursCateg, $dataCours, $member );
			$error = new error();
			$error->add_error( translate( 'cours_move_okay' ), ERROR_PAGE, __FILE__, __LINE__ );
		}
	break;
	
	case 'afficherTutoriel':
		$idTutoriel = intval( $_GET['idTutoriel'] );
		$cours = new Cours( $idTutoriel );
	break;
}
tpl_begin();
switch( $action )
{
	case 'voirArborescence':
		$cours = new Cours();
		$cours->racine()->elements_enfants_arborescence();
	break;
	
	case 'afficherTutoriel':
		$cours->affiche_cours();
	break;
	case 'voirListeTous':
		$dataCours = $cours->recupere_cours();
		?>
		<table>
			<tr>
				<th>Nom</th>
				<th>Actions</th>
			</tr>
		<?php
		foreach( $dataCours AS $v )
		{
			echo '<tr><td>';
			echo '<a href="?action=afficherTutoriel&idTutoriel=' . $v->getId() . '">' . $v->affiche_inline() . '</a>';
			echo '</td>';
			echo '<td><a href="?action=deplacerTutoriel&idTutoriel=' . $v->getId() . '">Déplacer</a>, <a href="?action=modifierTutoriel&idTutoriel=' . $v->getId() . '">Modifier</a>, <a href="?action=supprimerTutoriel&idTutoriel=' . $v->getId() . '">Supprimer</a></td></tr>';
		}
		?>
		</table>
		<?php
	break;
	
	case 'ajouterTutoriel':
	case 'modifierTutoriel':
	case 'deplacerTutoriel':
		$form->build_all();
	break;
}
echo '<p><a href="?action=voirListeTous">' . translate( 'back_to_list' ) . '</a> & <a href="?action=ajouterTutoriel">' . translate( 'add_a_tuto' ) .'</a></p>';
tpl_end();
?>

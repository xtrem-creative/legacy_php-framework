<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'membres', 'profil' );

if( isset( $_GET['idMembre'] ) )
{
	$idMembre = (int)$_GET['idMembre'];
	if( $bdd->count_sql( TABLE_MEMBERS, 'WHERE membre_id = ?', $idMembre ) != 1 )
	{
		echo 'boo';
		$error = new Error();
		$error->add_error( translate( 'member_not_found' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/membres/liste.php' );
	}
}
else
	$idMembre = $member->getId();
if( $idMembre == 0 )
{
	$error = new Error();
	$error->add_error( translate( 'offline' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/membres/connexion.php' );
}
//Si une action
if( isset( $_GET['action'] ) )
{
	switch( $_GET['action'] )
	{
		case 'voir':
			goto voirAction;
		break;
		
		case 'modifier':
			$action = 'modifier';
			if( !$member->verif_rank( RANK_ADMIN ) && $idMembre != $member->getId() )
				$idMembre = $member->getId();
			$query = $bdd->query( 'SELECT membre_id, membre_login, membre_email, membre_citation, membre_biographie, membre_design, membre_lang FROM ' . TABLE_MEMBERS . ' WHERE membre_id = ?', $idMembre );
			$data = $bdd->fetch( $query );
		break;
		
		default:
			goto voirAction;
		break;
	}
}
//Sinon, voir profil.
else
{
	voirAction:
	$query = $bdd->query( 'SELECT membre_id, membre_login, membre_email, membre_register, membre_last_up, membre_citation, membre_biographie, membre_rank FROM ' . TABLE_MEMBERS . ' WHERE membre_id = ?', $idMembre );
	$data = $bdd->fetch( $query );
	$action = 'voir';
}

tpl_begin();
switch( $action )
{
	case 'voir':
	if( $member->verif_rank( RANK_ADMIN ) || $idMembre == $member->getId() )
		echo '<p><a href="?action=modifier&idMembre=' . $idMembre . '">' . translate( 'edit_profile' ) . '</a></p>';
?>
<h3>Profil de <?php echo htmlentities( $data['membre_login'] ); ?></h3>
<p>Email : <?php echo htmlentities( $data['membre_email'] ); ?></p>
<p>Rang : <?php echo translate( 'rank_' . $data['membre_rank'] ); ?></p>
<p>Inscrit <?php echo date_avancee( $data['membre_register'] ); ?></p>
<p>Dernière connexion <?php echo date_avancee( $data['membre_last_up'] ); ?></p>
<p>Citation : <?php echo htmlentities( $data['membre_citation'] ); ?></p>
<p>Biographie : <?php echo XTCode_decode( $data['membre_biographie'] ); ?></p>
<?php
	break;
	
	case 'modifier':
		$form = new Form( translate( 'edit_profile' ), 'post' );
		$form->add_fieldset();
		$form->add_input( 'login', 'login', translate( 'login_form' ), 'text', 'disabled' )->setValue( htmlentities( $data['membre_login'] ) );
		$form->add_input( 'email', 'email', translate( 'email_form' ) )->setValue(  htmlentities( $data['membre_email'] ) );
		$form->add_input( 'citation', 'citation', translate( 'citation_form' ) )->setValue(  htmlentities( $data['membre_citation'] ) );
		$form->add_textarea( 'biographie', 'biographie', translate( 'biographie_form' ) )->setValue( $data['membre_biographie'] );
		$form->add_button();
		
		$fh = new FormHandle( $form );
		$fh->handle();
		
		if( $fh->okay() )
		{
			$newEmail = $fh->get( 'email' );
			$newCitation = $fh->get( 'citation' );
			$newBiographie = $fh->get( 'biographie' );
			
			$bdd->query( 'UPDATE ' . TABLE_MEMBERS . ' SET membre_email = ?, membre_citation = ?, membre_biographie = ? WHERE membre_id = ?', array( $newEmail, $newCitation, $newBiographie ) );
			$error = new Error();
			$error->add_error( translate( 'edit_ok' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/membres/connexion.php' );
			goto voirAction;
		}
		
		$form->build_all();
	break;
}
tpl_end();
?>

<?php
require_once( '../../kernel/begin.php' );
require_once( 'panel_admin.inc.php' );
$requeteConfiguration = $bdd->query( 'SELECT * FROM ' . TABLE_CONFIG );
$listeForms = array();
while( $configurations = $bdd->fetch( $requeteConfiguration ) )
{
	$form = new Form( translate( 'edit_config_title' ) );
	$form->add_fieldset();
	$form->add_input( $configurations['config_name'] . '_config_name', $configurations['config_name'] . '_config_name', translate( 'config_name' ) )->setValue( $configurations['config_name'] );
	$configsDatas = unserialize( $configurations['config_value'] );
	$i = 0;
	foreach( $configsDatas AS $k => $config )
	{
		$i++;
		$form->add_input( $configurations['config_name'] . '_config_entry_name_' . $i, $configurations['config_name'] . '_config_entry_name_' . $i, translate( 'config_entry_name' ) )->setValue( htmlspecialchars( $k ) );
		$form->add_textarea( $configurations['config_name'] . '_config_entry_value_' . $i, $configurations['config_name'] . '_config_entry_value_' . $i, translate( 'config_entry_value' ) )->setValue( stripslashes( htmlspecialchars( $config ) ) );
	}
	$form->add_input( $configurations['config_name'] . '_config_lang', 'config_lang', translate( 'config_lang' ) )->setValue( $configurations['config_lang'] );
	$form->add_button();
	$listeForms[$configurations['config_name']] = $form;
	unset( $form );
}
foreach( $listeForms AS $nomConfig => $formulaire )
{
	$traitement = new FormHandle( $formulaire );
	$traitement->handle();
	if( $traitement->okay() )
	{
		$langSite = $traitement->get( $nomConfig . '_config_lang' );
		$configValues = array();
		for( $i = 1; ( $nomEntree = $traitement->get( $nomConfig . '_config_entry_name_' . $i ) ) != NULL && ( $valeurEntree = $traitement->get( $nomConfig . '_config_entry_value_' . $i ) ) != NULL; $i++ )
			$configValues[$nomEntree] = addslashes( $valeurEntree );
		$configValues = serialize( $configValues );
		$bdd->query( 'UPDATE ' . TABLE_CONFIG . ' SET config_lang = ?, config_value = ? WHERE config_name = ?', 
				array( $langSite, $configValues, $nomConfig ) );
		$error = new Error();
		$error->add_error( translate( 'modification_success' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/admin/config.php' );
	}
}
tpl_begin();
foreach( $listeForms AS $form )
	$form->build_all();
tpl_end();
?>

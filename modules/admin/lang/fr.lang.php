<?php
$arrayLang = array(
    'default' => array(
        'not_admin'				=> 'Vous n\'êtes pas admin !',
        'not_online'			=> 'Vous n\'êtes pas connecté(e)  !',
    ),

	'cache'	=> array(
		'help_message'			=> 'Vous pouvez ici vider le cache sur le serveur afin de rafraîchir l\'affichage de certaines pages ou de certaines parties du site (menus, titre, etc.).',
		'wanna_refresh'			=> 'Voulez-vous rafraîchir le cache ?',
	),

    'index' => array(
		'welcome_message'		=> 'Bienvenue sur le panel d\'administration ! Les options qui vous sont données sont marquées dans le menu de gauche.',
    ),
    
    'config' => array(
		'edit_config_title'		=> 'Modifier la configuration du CMS',
		'config_name'			=> 'Nom de l\'entrée',
		'config_entry_name'		=> '--Nom du paramètre',
		'config_entry_value'	=> '--Valeur',
		'config_lang'			=> 'Langue',
		'modification_success'	=> 'La modification a réussi !',
    ),
    
    'infos_cms'	=> array(
		'compare_with_last'		=> 'Comparaison avec la dernière version officiel',
		'file_update_not_found'	=> 'Le fichier d\'update est introuvable.',
		'info_cms_version'		=> 'Informations sur la version installée du CMS',
		'no_update_found'		=> 'Aucune mise-à-jour disponible.',
		'version_of_is'			=> 'La version du %s est',
		'version_alpha'			=> 'La version alpha du CMS a été lancée à la révision 78.',
		'not_last_version'		=> 'Vous n\'avez plus la dernière version du <strong>%s</strong>. Celle disponible sur le site est la version : <strong>%s</strong>.<br />
Mettez à jour votre système.',
    ),
        
    'maj' => array(
		'file_form'				=> 'Fichier à uploader',
		'help_message'			=> 'Vous pouvez ici mettre votre CMS à jour. Attention, en uploadant un dossier, les fichiers que la MAJ doit modifier vont être écrasés. Vos modifications ne seront donc pas prises en compte.',
		'maj_fail'				=> 'La MAJ n\'a pas complètement réussi !',
		'maj_success'			=> 'La MAJ a réussi !',
		'maj_upload'			=> 'Upload du fichier',
		'new_files'				=> 'Fichiers nouveaux allant être créés',
		'updated_files'			=> 'Fichiers existants allant être mis à jour',
		'deleted_files'			=> 'Fichiers existants allant être supprimés',
    ),
    
    'menus'	=> array(
		'addition_successs'		=> 'L\'ajout a bien fonctionné !',
		'authorizations'		=> 'Autorisations',
		'delete_menu'			=> 'Supprimer un menu',
		'deletion_success'		=> 'La suppression a bien été appliquée !',
		'different_types'		=> 'Type 0 => Tout le monde, type 1 => Panel Membre, type 2 => Panel admin',
		'form_add'				=> 'Formulaire d\'ajout d\'un menu',
		'form_edit'				=> 'Formulaire de modification des menus',
		'html'					=> 'HTML ?',
		'link'					=> 'Valeur du lien',
		'modification_error'	=> 'Il y a eu une erreur lors de la modification des menus. Vous avez peut-être oublié de remplir certains champs.',
		'modification_success'	=> 'La modification a réussi ! Il est conseillé (mais pas obligatoire) de regénérer le cache après.',
		'order'					=> 'Numéro (ordre)',
		'position'				=> 'Position',
		'title'					=> 'Nom du lien',
		'type'					=> 'Type de lien',
    ),
    
    'paquets' => array(
		'help_message'			=> 'Cette page vous permet d\'installer des paquets pour un certain nombre de services du site. Pour ce faire, il vous faut uploader un fichier zippé que vous aurez trouvé sur le site officiel du CMS ou sur un site annexe.',
		'file_already_exists'	=> 'Le dossier/fichier existe déjà !',
		'file_form'				=> 'Fichier à uploader',
		'file_fail'				=> 'La MAJ n\'a pas complètement réussi !',
		'file_success'			=> 'La MAJ a réussi !',
		'file_upload'			=> 'Upload du fichier',
		
		'lang_help'				=> 'Pour installer une nouvelle langue, il vous faut un dossier zippé contenant les différents fichiers de la langue correspondante. Il peut arriver toutefois que certains modules n\'aient pas été traduits et donc il vous faudra rajouter encore un autre fichier.',
		'module_help'			=> 'Vous pouvez aussi installer un nouveau module à partir de ces formulaires. Il est possible que vous soient demandées quelques informations après l\'upload du fichier.',
		'design_help'			=> 'Il est aussi possible de charger un nouvel ensemble de styles, un nouveau design.',
    ),
    
    'templates'	=> array(
		'content_file'			=> 'Contenu du fichier',
		'file_form'				=> 'Modifier les fichiers',
		'help_message'			=> 'Ce panel vous permet de gérer les fichiers de templates (header, footer, etc.). Vous pouvez les modifier uniquement car ils sont vitaux pour le fonctionnement du site.<br />
 La liste est automatique, il est possible que certains fichiers affichés ne vous disent rien, ils sont pourtant utiles au site, évitez donc de les modifier si vous ne savez pas à quoi ils servent.',
		'modification_ok'		=> 'Les modifications ont bien été appliquées !',
    ),
    
);
?>

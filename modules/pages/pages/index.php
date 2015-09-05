<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'pages', 'index' );
$nomPage = ( isset( $_GET['page'] ) ? $_GET['page'] : 'index' );
$requetePage = $bdd->query( 'SELECT p.page_nom, p.page_id, p.page_titre, p.page_texte, m.membre_login, p.page_date FROM ' . TABLE_PAGES . ' AS p 
								LEFT JOIN ' . TABLE_MEMBERS . ' AS m ON m.membre_id = p.page_auteur WHERE p.page_url = ?', array( $nomPage ) );
$donneesPage = $bdd->fetch( $requetePage );
tplBegin();
?>
<h2><?php echo htmlspecialchars( $donneesPage['page_titre'] ); ?></h2>
<p><?php echo htmlspecialchars( $donneesPage['page_texte'] ); ?></p>
<?php
tplEnd();
?>

<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'accueil', 'index' );
tpl_begin();
$req = $bdd->query( 'SELECT categorie_nom, news_id, news_titre, news_categorie, news_auteur, news_contenu, membre_login, DATE_FORMAT(news_creation, "%d/%m/%Y à %Hh%i") AS news_creation, news_modification 
FROM ' . TABLE_NEWS . ' LEFT JOIN ' . TABLE_MEMBERS . ' ON membre_id = news_auteur LEFT JOIN ' . TABLE_NEWS_CATS . ' ON categorie_id = news_categorie ORDER BY news_id DESC' );
while( $data = $bdd->fetch( $req ) )
{
?>
<h1><?php echo htmlentities( $data['news_titre'] ); ?></h1>
<p style="font-size: 0.9em; color: rgb(83, 88, 106); margin-left: 20px; padding-left: 25px; font-weight: bold; background: url('<?php echo DESIGN; ?>img/infos_news.png') no-repeat scroll 0% 0% transparent; border-bottom: 1px solid rgb(230, 230, 250);">
<?php echo translate( 'by_at_in', htmlentities( $data['membre_login'] ), $data['news_creation'], htmlentities( $data['categorie_nom'] ) ); ?>
</p>

<div id="newsContenu">
	<?php echo XTCode_decode( $data['news_contenu'] ); ?>
</div>
<?php
}
tpl_end();
?>

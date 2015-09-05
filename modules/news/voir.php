<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'news', 'voir' );
$xtcode = new XTCode();

if( !isset( $_GET['id'] ) || empty( $_GET['id'] ) )
{
	$error = new Error();
	$error->add_error( translate( 'inexistant_news' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/news/index.php' );
}
else
{
	$_GET['id'] = (int) $_GET['id'];
	$newsVerif = $bdd->count_sql( TABLE_NEWS, 'WHERE news_id = ?', $_GET['id'] );
	if( $newsVerif == 0 )
	{
		$error = new Error();
		$error->add_error( translate( 'inexistant_news' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/news/index.php' );
	}
	else
	{
		$req = $bdd->query( 'SELECT categorie_nom, news_id, news_titre, news_categorie, news_auteur, news_contenu, membre_login, DATE_FORMAT(news_creation, "%d/%m/%Y à %Hh%i") AS news_creation, news_modification 
FROM ' . TABLE_NEWS . ' LEFT JOIN ' . TABLE_MEMBERS . ' ON membre_id = news_auteur LEFT JOIN ' . TABLE_NEWS_CATS . ' ON categorie_id = news_categorie WHERE news_id = ? ORDER BY news_id DESC', $_GET['id'] );
		while( $data = $bdd->fetch( $req ) )
		{
			$data['news_titre'] = htmlspecialchars(stripslashes($data['news_titre']));
			$data['categorie_nom'] = htmlspecialchars(stripslashes($data['categorie_nom']));
			$data['news_contenu'] = stripslashes( XTCode_decode( $data['news_contenu'] ) );
			
			$breadcrumb->add( translate( 'list_news' ), 'modules/news/index.php');
			$breadcrumb->add( $data['news_titre'] );
		
		tpl_begin();
		?>
			<h1><?php echo $data['news_titre']; ?></h1>
			<p style="font-size: 0.9em; color: rgb(83, 88, 106); margin-left: 20px; padding-left: 25px; font-weight: bold; background: url('<?php echo DESIGN; ?>img/infos_news.png') no-repeat scroll 0% 0% transparent; border-bottom: 1px solid rgb(230, 230, 250);">
				<?php echo translate( 'by_at_in', $data['membre_login'], $data['news_creation'], $data['categorie_nom'] ); ?>
			</p>
			
			<div id="newsContenu">
				<?php echo $data['news_contenu']; ?>
			</div>
			
			<p style="text-align:right; clear:both;"><a href="index.php"><?php echo translate( 'back_to_list' ); ?></a></p>
		<?php
		tpl_end();
		}
	}
}
?>

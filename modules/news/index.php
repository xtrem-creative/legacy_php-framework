<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'news', 'liste' );
$breadcrumb->add( translate( 'list_news' ) );
tpl_begin();
?>

<h1><?php echo translate( 'list_all_online' ); ?></h1>

<table style="width:100%; border-collapse: collapse; border-left:solid #90EE90 1px; border-right:solid #90EE90 1px;">
	<thead>
		<tr style="background:#005C00; color:#FFFFFF;">
			<th>#</th>
			<th><?php echo translate( 'title_news' ); ?></th>
			<th><?php echo translate( 'author_news' ); ?></th>
			<th><?php echo translate( 'category_news' ); ?></th>
			<th><?php echo translate( 'publication_news' ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr style="background:#005C00;">
			<th colspan="5"></th>
		</tr>
	</tfoot>
	<tbody>
	<?php
		$req = $bdd->query( 'SELECT categorie_nom, news_id, news_titre, news_categorie, news_auteur, news_contenu, membre_login, DATE_FORMAT(news_creation, "%d/%m/%Y à %Hh%i") AS news_creation, news_modification 
		FROM ' . TABLE_NEWS . ' LEFT JOIN ' . TABLE_MEMBERS . ' ON membre_id = news_auteur LEFT JOIN ' . TABLE_NEWS_CATS . ' ON categorie_id = news_categorie ORDER BY news_id DESC' );
		while( $data = $bdd->fetch( $req ) )
		{
		?>
		<tr style="border-bottom:solid 1px #E6E6FA;">
			<td style="text-align:center;"><?php echo $data['news_id']; ?></td>
			<td style="text-align:center;"><a href="voir.php?id=<?php echo $data['news_id']; ?>"><?php echo stripslashes( htmlspecialchars( $data['news_titre'] ) ); ?></a></td>
			<td style="text-align:center;"><?php echo htmlentities( $data['membre_login'] ); ?></td>
			<td style="text-align:center;"><?php echo htmlentities( $data['categorie_nom'] ); ?></td>
			<td style="text-align:center;"><?php echo $data['news_creation']; ?></td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<?php
tpl_end();
?>

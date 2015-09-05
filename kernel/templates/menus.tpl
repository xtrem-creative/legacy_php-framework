		<?php $menusContents = $tpl->menus()->get( 'menuDatas' );
		foreach( $menusContents AS $menusType => $menusDatas ) 
		{
			if( $menusType == 'right' )
				ob_start();
?>
	<div id="navigation_<?php echo $menusType; ?>">
		<ul>
<?php
			foreach( $menusDatas AS $link )
			{
				if( $link['type'] != $tpl->menus()->getType() && $link['type'] > 0 ) continue;
				$ok = false; 
				switch( $link['auths'] )
				{
					case 'all':
						$ok = true;
					break;
					
					case 'member':
						if( $member->is_connected() )
							$ok = true;
					break;
					
					case 'guest':
						if( !$member->is_connected() )
							$ok = true;
					break;
					
					case 'admin':
						if( $member->verif_rank( RANK_ADMIN ) )
							$ok = true;
					break;
				}
				if( $ok === true )
				{
				?>
					<li><a href="<?php echo $link['link']; ?>"><?php echo $link['label']; ?></a></li>
				<?php 
				}
			}
?>
		</ul>
	</div><!-- #navigation -->
<?php
			if( $menusType == 'footer' )
			{
				$tpl->footer()->add_menu_footer( ob_get_contents() );
				ob_end_clean();
			}
		} ?>

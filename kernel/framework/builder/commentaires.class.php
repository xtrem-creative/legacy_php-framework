<?php
class commentaires
{
    //Contient des informations sur la configuration principalement
    private $datas = array();
    //Contient le nom de la table correspondante pour les commentaires
    private $table = '';
    //Contient le nom du module
    private $module = '';
    //Contient tous les commentaires
    private $commentaires = array();
    private $bdd;
    
    public function __construct( $module, $id = false, $lectureSeule = false )
    {
		$this->bdd = new bdd();
		
        //Vérifie que la table existe : si non, bloque la suite du programme.
        if( defined( strtoupper( 'table_' . $module . '_commentaires' ) ) )
            eval( '$this->table = '.strtoupper( 'table_' . $module . '_commentaires' ).';' );
        else
            die( 'Impossible : constante de table non instanciée.' );
        //Entre les différentes informations
        $this->module = url_transform( $module );
        //Début de la table par défaut et limite de messages par pages (pour la pagination)
        $this->datas['debut'] = 0;
        $this->datas['limit'] = NB_COMS_PAGE;
        //TRÈS IMPORTANT : l'ID principal de l'objet parcouru (news, etc.)
        $this->datas['id_principal'] = ( $id ? $id : -1 );
        //Coms en lecture seule ?
        $this->datas['lectureSeule'] = ( $lectureSeule ? true : false ); 
    }
    
    public function panel( $id = false ) 
    {
        //Si on a décidé de (re)définir l'ID à ce niveau là, on le fait.
        if( $id ) $this->datas['id_principal'] = $id;
        //Suivant l'action, on trie
        if( isset( $_POST['com_send'] ) )
            return $this->ajouter();
        elseif( isset( $_GET['cite_commentaire'] ) )
            return $this->citer();
        elseif( isset( $_POST['com_modifier'] ) )
            return $this->modifier();
        elseif( isset( $_GET['modifier_commentaire'] ) )
            return $this->modifier();
        elseif( isset( $_GET['supprimer_commentaire'] ) )
            return $this->supprimer();
        else
            return $this->display( $id );
    }
    
    private function modifier()
    {
        if( isset( $_POST['com_modifier'] ) )
        {
            $contenu = XTCode_encode( $_POST['comm_contenu'] );
            $id_m = intval( $_GET['modifier_commentaire'] );
            //Le commentaire existe-t-il ?
            if( !$this->verifExist( $id_m ) )
            {
                $error = redirect( 'Ce commentaire n\'existe pas.', 3, URL, 2 );
                make_redirection( $error );
            }
            //On met à jour.
            $this->bdd->query( 'UPDATE '.$this->table.' SET contenu = ? WHERE id_commentaire = ?', array( $contenu, $id_m ) );
            $error = redirect( 'Ce commentaire a bien été modifié.', 4, URL, 1 );
            make_redirection( $error );
        }
        else
        {
            //On vérifie si le commentaire existe.
            $id_m = intval( $_GET['modifier_commentaire'] );
            if( !$this->verifExist( $id_m ) )
            {
                make_redirection( $error );
            }
            $sql = $this->bdd->query( 'SELECT contenu FROM '.$this->table.' WHERE id_commentaire = ?', $id_m );
            $data  = $this->bdd->fetch( $sql );
            //On peut afficher le formulaire de modification.
            $data['contenu'] = XTCode_xdecode( $data['contenu'] );
            ob_start();
            ?>
		    <form method="post" action="" id="form_modifier_com_<?php echo $id_m; ?>">
				    <fieldset>
					    <legend>Modifier un commentaire.</legend>
					    <p>
					    <?php echo xtcode_form( 'comm_contenu' ); ?>
					    <textarea name="comm_contenu" id="comm_contenu" cols="86" rows="10"><?php echo $data['contenu']; ?></textarea>
					    <?php echo previsualiser( 'comm_contenu' ); ?>
					    <input type="submit" name="com_modifier" id="com_modifier" />
					    </p>
				    </fieldset>
		    </form>
		    <?php
		    $return = ob_get_contents();
            ob_end_clean();
            return $return;
        }
    }
    
    private function citer()
    {
	    //On vérifie si le commentaire existe.
	    $id_m = intval( $_GET['cite_commentaire'] );
        if( !$this->verifExist( $id_m ) )
        {
    	    make_redirection( $error );
        }
        $sql = $this->bdd->query( 'SELECT contenu, auteur AS id_auteur, membres.pseudo AS auteur FROM '.$this->table.' LEFT JOIN membres ON auteur = membres.id WHERE id_commentaire = ?', $id_m );
        $data  = $this->bdd->fetch( $sql );
        //On peut afficher le formulaire de modification.
        $data['contenu'] = '|citation="'.$data['auteur'].'"|'.XTCode_xdecode( $data['contenu'] ).'|/citation|';
        ob_start();
        ?>
		    <form method="post" action="" id="form_cite_com_<?php echo $id_m; ?>">
				    <fieldset>
					    <legend>Ajouter un commentaire à partir d'une citation.</legend>
					    <p>
					    <?php echo xtcode_form( 'comm_contenu' ); ?>
					    <textarea name="comm_contenu" id="comm_contenu" cols="86" rows="10"><?php echo $data['contenu']; ?></textarea>
					    <?php echo previsualiser( 'comm_contenu' ); ?>
					    </p>
				    </fieldset>
				    <p style="text-align:right;">
					   	<input type="submit" name="com_send" id="com_send" />
					</p>
		    </form>
		<?php
		$return = ob_get_contents();
	    ob_end_clean();
		return $return;
	}
    
    private function ajouter()
    {
        global $cache;
        if( !isset( $_SESSION['id'] ) ) exit;
        if( $this->datas['id_principal'] == -1 ) exit( 'Pas d\'ID' );
        if( $this->datas['lectureSeule'] == true )       		
        	make_redirection( redirect( 'L\'ajout de commentaires est verrouillé.', 100, URL, 2 ) );
        	
        $id_module = intval( $this->datas['id_principal'] );
        $contenu = XTCode_encode( $_POST['comm_contenu'] );
		$this->bdd->query( 'INSERT INTO ' . $this->table . '(id_module, auteur, contenu, timestamp) VALUES(?, ?, ?, ?)', array( $id_module, intval( $_SESSION['id'] ), $contenu, time() ) );
		if( $this->module == 'taches' )
		{
		    $dests = $this->bdd->fetch( $this->bdd->query( 'SELECT assignee FROM '.TABLE_TACHES.' WHERE id = ?', $id_module ) );
		   //if( $dests['assignee'] > 1 && $dests['assignee'] != $_SESSION['id'] )
		   //     addMp( $dests['assignee'], 'Nouveau commentaire sur le panel des tâches', '<paragraphe>Nouveau commentaire sur la <lien vers="'.RACINE.'developpeurs/taches-'.$id_module.'-commenter.html">tâche #'.$id_module.'</lien><paragraphe>', true );
		}
        $idCom = lastInsertId();
        $error = redirect( 'Le commentaire a bien été posté.', 5, URL, 1 );
       // $cache->deleteIDCache( $id_module, 'contents', 'nbComs' . ucfirst( $this->module ) );
        make_redirection( $error );
    }
    
    private function supprimer()
    {
        global $rangs, $cache;
        $id = intval( $_GET['supprimer_commentaire'] );
        if( !$this->verifExist( $id ) ) 
        {
            $error = redirect( 'Ce commentaire n\'existe pas.', 3, URL, 2 );
            make_redirection( $error );
        }
        if( $rangs->verif( 'supprimer' ) || $rangs->verif( 'moderer' ) ){
            $this->bdd->query( 'DELETE FROM '.$this->table.' WHERE id_commentaire = ?', $id );
            $cache->deleteIDCache( $this->datas['id_principal'], 'contents', 'nbComs' . ucfirst( $this->module ) );
        }
        $error = redirect( 'Ce commentaire a bien été supprimé.', 6, URL, 1 );
        make_redirection( $error );
    }
        
    private function getComs()
    {
        //On récupère l'ID de l'objet parcouru et les informations importantes
        $id_module = $this->datas['id_principal'];
        //Si ID original, on bloque.
        if( $id_module == -1 ) return false;
        $debut = $this->datas['debut'];
        $limit = $this->datas['limit'];
        //On prépare la pagination
        $nb_coms = $this->getNbreComs( $id_module );
        $nb_pages = ceil( $nb_coms / NB_COMS_PAGE );
        if(isset($_GET['page_commentaires']))
        {
	        $page_actuelle = intval ($_GET['page_commentaires']);
	        if ($page_actuelle>$nb_pages) 
		        $page_actuelle = $nb_pages;
        }
        else
            $page_actuelle = 1;
        $entree = ($page_actuelle - 1)*NB_COMS_PAGE;
        $pagination = $this->pagination( $nb_pages, $page_actuelle );
        //On stocke la pagination.
        $this->datas['pagination'] = $pagination;
        //On vérifie l'existence du commentaire.
        if( $nb_coms == 0 ) 
        {
            $this->datas['no_com'] = true;
            return true;
        }
        $this->datas['nb_commentaires'] = $nb_coms;
        //On lance la requête
        $sql = $this->bdd->query( 'SELECT `id_commentaire`, `id_module`, m.`pseudo`, m.`avatar`, m.`signature`, m.`citation`, m.`site_web`, `auteur`, `contenu`, `timestamp` FROM '.$this->table.' AS p LEFT JOIN '.TABLE_MEMBRES.' AS m ON m.id = p.auteur WHERE `id_module` = ? ORDER BY `timestamp` DESC LIMIT '.intval( $entree ).','.intval( $limit ).'', $id_module );
        $datas = array();
        while( $data = $this->bdd->fetch( $sql ) )
        {
            //On parse l'ensemble des données.
            $data['avatarOnOff'] = est_en_ligne( $data['pseudo'] ) ? 'avatarAuteurOn' : 'avatarAuteurOff';
            $data['pseudo'] = htmlspecialchars( $data['pseudo'] );
            $data['pseudo_url'] = url_transform( $data['pseudo'] );
            $data['contenu'] = XTCode_decode( $data['contenu'] );
            $data['timestamp'] = date_avancee( $data['timestamp'] );
            $data['avatar'] = htmlspecialchars( $data['avatar'] );
            $data['citation'] = htmlspecialchars( stripslashes( $data['citation'] ) );
            $data['signature'] = XTCode_decode( $data['signature'] );
            $data['site_web'] = htmlspecialchars( stripslashes( $data['site_web'] ) );
            if( empty( $data['site_web'] ) ) unset( $data['site_web'] );
            $datas[] = $data;
        }
        //On stocke les données
        $this->commentaires = $datas;
        return true;
    }
    
    private function display( $id_module = 1, $debut = 0, $limit = NB_COMS_PAGE )
    {
        //On vérifie si les commentaires ont bien été choisis.
        $this->datas['id_principal'] = ( $id_module ) ? intval( $id_module ) : $this->datas['id_principal'];
        $this->datas['debut'] = intval( $debut );
        $this->datas['limit'] = intval( $limit );
        if( !$this->getComs() )
        {
            $error = redirect( 'Aucun ID valide n\'a été spécifié pour les arguments.', 7, URL, 2 );
            make_redirection( $error );
        }
        
        global $tpl, $rangs;
        ob_start();
        if( isset( $this->datas['no_com'] ) )
        {
        ?>
			<h3 style="text-align:center;">Aucun commentaire</h3>
		<?php
		}
		else
		{
		    if( $this->datas['nb_commentaires'] == 1 )
		    {
		?>
				<h3 id="commentaires">1 Commentaire</h3>
		<?php
		    }
		    else
		    {
		?>
				<h3 id="commentaires"><?php echo $this->datas['nb_commentaires']; ?> Commentaires</h3>
		<?php
		    }
		//pagination
	    echo $this->datas['pagination'];
		?>

		<div id="commentairesTutoriels">
		<?php
		foreach( $this->commentaires AS $val )
		{
		?>
		<div class="blocCommentaire" id="com<?php echo $val['id_commentaire']; ?>">
			<div class="infosAuteur"><p><img src="<?php echo $val['avatar']; ?>" alt="" class="<?php echo $val['avatarOnOff']; ?>" /><br />
			<?php echo parse_membre( $val['auteur'] ); ?>
			</p>
			<p class="optionsAuteur">
				<?php
				if( isset( $_SESSION['id'] ) && $val['auteur'] == $_SESSION['id'] )
				{
				?>
					<a href="<?php echo RACINE; ?>edit-profil<?php echo $val['auteur']; ?>.html"><img src="<?php echo DESIGN; ?>images/editer.png" alt="" /></a> 
				<?php
				}
				else
				{
				?>
					<a href="<?php echo RACINE; ?>messagerie-nouveau-<?php echo $val['auteur']; ?>-<?php echo $val['auteur']; ?>.html"><img src="<?php echo DESIGN; ?>images/mp_close.png" alt="" /></a> 
				<?php 
				}
				if( isset( $val['site_web'] ) )
				{
				?>
					<a href="<?php echo $val['site_web']; ?>"><img src="<?php echo DESIGN; ?>images/site_web.png" alt="" /></a>
				<?php
				}
				?>
			</p>
			<?php
			if( isset( $val['citation'] ) && !empty( $val['citation'] ) )
			{
			?>
			<p class="citationAuteur"><?php echo $val['citation']; ?></p>
			<?php
			}
			?>
			</div>
			<div class="informationsCommentaire">
				<p><a href="#com<?php echo $val['id_commentaire']; ?>">#</a> Posté <?php echo $val['timestamp'];
					if( isset( $_SESSION['id'] ) )
					{
					?>
					 - <a href="<?php echo URL; ?>?cite_commentaire=<?php echo $val['id_commentaire']; ?>#form_cite_com_<?php echo $val['id_commentaire']; ?>"><img src="<?php echo DESIGN; ?>images/citer.png" alt="Citer" title="Citer ce message" /></a>
					<?php
					}
					if( isset( $_SESSION['id'] ) && ( ( $val['auteur'] == $_SESSION['id'] ) || $rangs->verif( 'moderer' ) ) )
					{
						if( $rangs->verif( 'modifier' ) || $rangs->verif( 'moderer' ) )
					    {
			        	?>
							<a href="<?php echo URL; ?>?modifier_commentaire=<?php echo $val['id_commentaire']; ?>#form_modifier_com_<?php echo $val['id_commentaire']; ?>"><img src="<?php echo DESIGN; ?>images/editer.png" title="Modifier le commentaire ?" alt="Modifier le commentaire" /></a>
						<?php
					    }
					    
					    if( $rangs->verif( 'supprimer' ) || $rangs->verif( 'moderer' ) )
					    {
						?>
							<a href="<?php echo URL; ?>?supprimer_commentaire=<?php echo $val['id_commentaire']; ?>"><img src="<?php echo DESIGN; ?>images/supprimer.png" title="Supprimer le commentaire" alt="Supprimer ?" /></a>
					<?php
					    }				    
					}
					?> 
				</p>
			</div>
			<div class="contenuCommentaire">
				<?php echo $val['contenu']; ?>
			</div>
			<?php
			if( isset( $val['signature'] ) && !empty( $val['signature'] ) )
			{
			?>
			<div class="signatureAuteur">
					<?php echo $val['signature']; ?>
			</div>
			<?php
			}
			?>
		</div>
	<?php
	}
	?>
	</div>
		<?php
		echo $this->datas['pagination'];
		}
		?>
		<hr />
		
		<?php
		if( $tpl->get( 'IS_CONNECTED' ) && $this->datas['lectureSeule'] == false )
		{
		?>
		<form method="post" action="">
				<fieldset>
					<legend>Ajouter un commentaire.</legend>
					<p>
					<?php echo xtcode_form( 'comm_contenu' ); ?>
					<textarea name="comm_contenu" id="comm_contenu" cols="86" rows="10"></textarea>
					<?php echo previsualiser( 'comm_contenu' ); ?>
					<input type="hidden" name="comm_id" id="comm_id" value="<?php echo $this->datas['id_principal']; ?>" /><br/>
					<input type="submit" name="com_send" id="com_send" />
					</p>
				</fieldset>
		</form>
		<?php
		}
		else
		{
			if( $tpl->get( 'IS_CONNECTED' ) ) 
			{
			?>
				<p style="text-align:center; display:block;">L'ajout de commentaires est verrouillé.</p>
			<?php
			}
			else
			{
			?>
				<p style="color:red; text-align:center; display:block;"><a href="/connexion.html">Connectez-vous</a> pour commenter !</p>
			<?php
			}
        }
        $return = ob_get_contents();
        ob_end_clean();
        return $return;
    }
    
    private function verifExist( $idcom )
    {
        global $rangs;
        $verif = $this->bdd->countSql( 'nb', $this->table, 'WHERE id_commentaire = ? AND auteur = ?', array( $idcom, $_SESSION['id'] ) );
        //On vérifie si le commentaire existe et si on a les droits pour le modifier.
        if( $verif['nb'] == 0 && ( !$rangs->verif( 'modifier' ) && !$rangs->verif( 'moderer' ) ) )
            return false;
        return true;
    }
    
    public function getNbreComs( $id_module )
    {
        global $cache;
        $cacheNbComs = $cache->getInfosCache( 'nbComs' . ucfirst( $this->module ) );
        if( empty( $cacheNbComs ) || !isset( $cacheNbComs[$id_module] ) )
	    {
            //On récupère le nombre de commentaires
            $nb_coms = $this->bdd->countSql( 'nb', $this->table, 'WHERE id_module = ?', $id_module );
            $cacheNbComs[$id_module] = $nb_coms['nb'];
            $cache->createCache( serialize( $cacheNbComs ), 'contents', 'nbComs' . ucfirst( $this->module ) );
        }
        else
            $nb_coms['nb'] = $cacheNbComs[$id_module];
        return $nb_coms['nb'];
    }
    
    private function pagination( $nb_pages, $page_actuelle = 1 )
    {
        $ecart_pages_membres = NB_COMS_PAGE;
        $get = '?page_commentaires=';
        if( $nb_pages <= 1 ) return '';
	    ob_start();
	    ?>
	    <strong>Afficher la page</strong> : 
	    <?php
	    $sep = 0;
	    $array = array();
	    for($i=1; $i<=$nb_pages; $i++) 
	    {
		    $sous_1 = $nb_pages - $i;
		    $sous_2 = $page_actuelle - $i;
		    $sous_3 = $i - $page_actuelle;
		    if( $i == $page_actuelle ) 
		    {
			    echo ' [ '.$i.' ] '; 
		    }
		    elseif( $sous_1 <= $ecart_pages_membres || $i <= $ecart_pages_membres || ( $sous_2 <= $ecart_pages_membres && $sous_2 > 0 ) || ( $sous_3 <= $ecart_pages_membres && $sous_3 > 0 ) )
		    {
			    $sep = 0;
			    echo '<a href="'.URL.$get.$i.'#commentaires">'.$i.'</a> ';
		    }
		    else
		    {
			    $sep++;
			    if( $sep == 1 )
			    echo '...';
			
		    }
	    }
	    $pagination = ob_get_contents(); 
	    ob_end_clean();
	    return '<p style="text-align:center;">'.$pagination.'</p>';
    }
}
?>

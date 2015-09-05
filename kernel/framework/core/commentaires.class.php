<?php
define( 'URL', 'http://' . $_SERVER['HTTP_HOST'] . '/' . ltrim( $_SERVER['PHP_SELF'], '/' ) );
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
    const NB_COMS_PAGE = 10;

    public function __construct( $module, $id = false, $lectureSeule = false )
    {
        //Vérifie que la table existe : si non, bloque la suite du programme.
        if( defined( strtoupper( 'table_' . $module . '_commentaires' ) ) )
            eval( '$this->table = '.strtoupper( 'table_' . $module . '_commentaires' ).';' );
        else
            die( 'Impossible : constante de table non instanciée.' );
        //Entre les différentes informations
        $this->module = url_transform( $module );
        //Début de la table par défaut et limite de messages par pages (pour la pagination)
        $this->datas['debut'] = 0;
        $this->datas['limit'] = self::NB_COMS_PAGE;
        //TRÈS IMPORTANT : l'ID principal de l'objet parcouru (news, etc.)
        $this->datas['id_principal'] = ( $id ) ? $id : -1;
        //Coms en lecture seule ?
        $this->datas['lectureSeule'] = ( $lectureSeule ) ? true : false; 
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
		global $bdd;
        if( isset( $_POST['com_modifier'] ) )
        {
            $commentaire_contenu = XTCode_encode( $_POST['comm_contenu'] );
            $id_m = intval( $_GET['modifier_commentaire'] );
            //Le commentaire existe-t-il ?
            if( !$this->verifExist( $id_m ) )
                $error = error( 'Ce commentaire n\'existe pas.' );
            //On met à jour.
            $bdd->query( 'UPDATE '.$this->table.' SET commentaire_contenu = ? WHERE commentaire_id = ?', array( $commentaire_contenu, $id_m ) );
            $error = error( 'Ce commentaire a bien été modifié.' );
        }
        else
        {
            //On vérifie si le commentaire existe.
            $id_m = intval( $_GET['modifier_commentaire'] );
            if( !$this->verifExist( $id_m ) )
            {
                make_redirection( $error );
            }
            $sql = $bdd->query( 'SELECT commentaire_contenu FROM '.$this->table.' WHERE commentaire_id = ?', $id_m );
            $data  = $bdd->fetch( $sql );
            //On peut afficher le formulaire de modification.
            $data['commentaire_contenu'] = XTCode_xdecode( $data['commentaire_contenu'] );
            ob_start();
            ?>
		    <form method="post" action="" id="form_modifier_com_<?php echo $id_m; ?>">
				    <fieldset>
					    <legend>Modifier un commentaire.</legend>
					    <p>
					    <?php // echo xtcode_form( 'comm_contenu' ); ?>
					    <textarea name="comm_contenu" id="comm_contenu" cols="86" rows="10"><?php echo $data['commentaire_contenu']; ?></textarea>
					    <?php // echo previsualiser( 'comm_contenu' ); ?>
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
        $sql = $bdd->query( 'SELECT commentaire_contenu, commentaire_auteur AS id_commentaire_auteur, membres.pseudo AS commentaire_auteur FROM '.$this->table.' LEFT JOIN membres ON commentaire_auteur = membres.id WHERE commentaire_id = ?', $id_m );
        $data  = fetch( $sql );
        //On peut afficher le formulaire de modification.
        $data['commentaire_contenu'] = '|citation="'.$data['commentaire_auteur'].'"|'.XTCode_xdecode( $data['commentaire_contenu'] ).'|/citation|';
        ob_start();
        ?>
		    <form method="post" action="" id="form_cite_com_<?php echo $id_m; ?>">
				    <fieldset>
					    <legend>Ajouter un commentaire à partir d'une citation.</legend>
					    <p>
					    <?php // echo xtcode_form( 'comm_contenu' ); ?>
					    <textarea name="comm_contenu" id="comm_contenu" cols="86" rows="10"><?php echo $data['commentaire_contenu']; ?></textarea>
					    <?php // echo previsualiser( 'comm_contenu' ); ?>
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
        global $cache, $member, $bdd;
        if( !$member->is_connected() ) exit;
        if( $this->datas['id_principal'] == -1 ) exit( 'Pas d\'ID' );
        if( $this->datas['lectureSeule'] == true )       		
        	make_redirection( redirect( 'L\'ajout de commentaires est verrouillé.', 100, URL, 2 ) );
        $module_id = intval( $this->datas['id_principal'] );
        $commentaire_contenu = XTCode_encode( $_POST['comm_contenu'] );
		$bdd->query( 'INSERT INTO ' . $this->table . '(module_id, commentaire_auteur, commentaire_contenu, commentaire_date) VALUES(?, ?, ?, ?)', array( $module_id, intval( $_SESSION['__member']['id'] ), $commentaire_contenu, time() ) );
        $this->refresh_cache( $module_id );
        $error = error( 'Le commentaire a bien été posté.', 5, URL, 1 );
    }
    
    private function supprimer()
    {
        global $rights, $cache, $bdd;
        $module_id = intval( $_GET['supprimer_commentaire'] );
        if( !$this->verifExist( $module_id ) ) 
        {
            $error = error( 'Ce commentaire n\'existe pas.' );
        }
        if( $rights->verif( RIGHT_DELETE ) || $rights->verif( RIGHT_MODERATE ) ){
            $bdd->query( 'DELETE FROM '.$this->table.' WHERE commentaire_id = ?', $module_id );
            $this->refresh_cache( $module_id );
        }
        $error = error( 'Ce commentaire a bien été supprimé.' );
    }
        
    private function getComs()
    {
		global $bdd;
        //On récupère l'ID de l'objet parcouru et les informations importantes
        $module_id = $this->datas['id_principal'];
        //Si ID original, on bloque.
        if( $module_id == -1 ) return false;
        $debut = $this->datas['debut'];
        $limit = $this->datas['limit'];
        //On prépare la pagination
        $nb_coms = $this->getNbreComs( $module_id );
        $nb_pages = ceil( $nb_coms / self::NB_COMS_PAGE );
        if(isset($_GET['page_commentaires']))
        {
	        $page_actuelle = intval ($_GET['page_commentaires']);
	        if ($page_actuelle>$nb_pages) 
		        $page_actuelle = $nb_pages;
        }
        else
            $page_actuelle = 1;
        $entree = ($page_actuelle - 1)*self::NB_COMS_PAGE;
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
        $sql = $bdd->query( 'SELECT `commentaire_id`, `module_id`, m.`membre_login`, m.`membre_avatar`, m.`membre_biographie`, m.`membre_citation`, 
        m.`membre_email` AS `site_web`, `commentaire_auteur`, `commentaire_contenu`, `commentaire_date` FROM '.$this->table.' AS p 
        LEFT JOIN '.TABLE_MEMBERS.' AS m ON m.membre_id = p.commentaire_auteur WHERE `module_id` = ? ORDER BY `commentaire_date` DESC LIMIT '.intval( $entree ).','.intval( $limit ).'', $module_id );
        $datas = array();
        while( $data = $bdd->fetch( $sql ) )
        {
            //On parse l'ensemble des données.
            $data['avatarOnOff'] = est_en_ligne( $data['membre_login'] ) ? 'avatarcommentaire_auteurOn' : 'avatarcommentaire_auteurOff';
            $data['pseudo'] = htmlspecialchars( $data['membre_login'] );
            $data['pseudo_url'] = url_transform( $data['membre_login'] );
            $data['commentaire_contenu'] = XTCode_decode( $data['commentaire_contenu'] );
            $data['commentaire_date'] = date_avancee( $data['commentaire_date'] );
            $data['avatar'] = htmlspecialchars( $data['membre_avatar'] );
            $data['citation'] = htmlspecialchars( stripslashes( $data['membre_citation'] ) );
            $data['signature'] = XTCode_decode( $data['membre_biographie'] );
            $data['site_web'] = explode(";", $data['site_web']);
            $data['nbDeSites'] = count($data['site_web']);
            $data['site_web'] = $data['site_web'][array_rand($data['site_web'])];
            $data['site_web'] = htmlspecialchars( stripslashes( $data['site_web'] ) );
            if( empty( $data['site_web'] ) ) unset( $data['site_web'] );
            $datas[] = $data;
        }
        //On stocke les données
        $this->commentaires = $datas;
        return true;
    }
    
    private function display( $module_id = 1, $debut = 0, $limit = self::NB_COMS_PAGE )
    {
		global $member, $rights;
        //On vérifie si les commentaires ont bien été choisis.
        $this->datas['id_principal'] = ( $module_id ) ? intval( $module_id ) : $this->datas['id_principal'];
        $this->datas['debut'] = intval( $debut );
        $this->datas['limit'] = intval( $limit );
        if( !$this->getComs() )
        {
            $error = redirect( 'Aucun ID valide n\'a été spécifié pour les arguments.', 7, URL, 2 );
            make_redirection( $error );
        }
        
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
		<div class="blocCommentaire" id="com<?php echo $val['commentaire_id']; ?>">
			<div class="infoscommentaire_auteur"><p><img src="<?php echo $val['avatar']; ?>" alt="" class="<?php echo $val['avatarOnOff']; ?>" /><br />
			<?php echo parse_membre( $val['commentaire_auteur'] ); ?>
			</p>
			<p class="optionscommentaire_auteur">
				<?php
				if( isset( $_SESSION['__member']['id'] ) && $val['commentaire_auteur'] == $_SESSION['__member']['id'] )
				{
				?>
					<a href="<?php echo ROOTU; ?>edit-profil<?php echo $val['commentaire_auteur']; ?>.html"><img src="<?php echo DESIGN; ?>images/editer.png" alt="" /></a> 
				<?php
				}
				else
				{
				?>
					<a href="<?php echo ROOTU; ?>messagerie-nouveau-<?php echo $val['commentaire_auteur']; ?>-<?php echo $val['commentaire_auteur']; ?>.html"><img src="<?php echo DESIGN; ?>images/mp_close.png" alt="" /></a> 
				<?php 
				}
				if( isset( $val['site_web'] ) )
				{
				?>
					<a href="<?php echo $val['site_web']; ?>">
					<?php if( $val['nbDeSites'] == 1 ) 
					{?>
						<img src="<?php echo DESIGN; ?>images/site_web.png" alt="" />
					<?php
					}
					else
					{
					?>
						<img src="<?php echo DESIGN; ?>images/sites_web.png" title="Ce membre possède plusieurs sites web." alt="" />
					<?php
					}
					?>
					</a>
				<?php
				}
				?>
			</p>
			<?php
			if( isset( $val['citation'] ) && !empty( $val['citation'] ) )
			{
			?>
			<p class="citationcommentaire_auteur"><?php echo $val['citation']; ?></p>
			<?php
			}
			?>
			</div>
			<div class="informationsCommentaire">
				<p><a href="#com<?php echo $val['commentaire_id']; ?>">#</a> Posté <?php echo $val['commentaire_date'];
					if( isset( $_SESSION['__member']['id'] ) )
					{
					?>
					 - <a href="<?php echo URL; ?>?cite_commentaire=<?php echo $val['commentaire_id']; ?>#form_cite_com_<?php echo $val['commentaire_id']; ?>"><img src="<?php echo DESIGN; ?>images/citer.png" alt="Citer" title="Citer ce message" /></a>
					<?php
					}
					if( isset( $_SESSION['__member']['id'] ) && ( ( $val['commentaire_auteur'] == $_SESSION['__member']['id'] ) || $rights->verif( RIGHT_MODERATE ) ) )
					{
						if( $rights->verif( RIGHT_MODIFY ) || $rights->verif( RIGHT_MODERATE ) )
					    {
			        	?>
							<a href="<?php echo URL; ?>?modifier_commentaire=<?php echo $val['commentaire_id']; ?>#form_modifier_com_<?php echo $val['commentaire_id']; ?>"><img src="<?php echo DESIGN; ?>images/editer.png" title="Modifier le commentaire ?" alt="Modifier le commentaire" /></a>
						<?php
					    }
					    
					    if( $rights->verif( RIGHT_DELETE ) || $rights->verif( RIGHT_MODIFY ) )
					    {
						?>
							<a href="<?php echo URL; ?>?supprimer_commentaire=<?php echo $val['commentaire_id']; ?>"><img src="<?php echo DESIGN; ?>images/supprimer.png" title="Supprimer le commentaire" alt="Supprimer ?" /></a>
					<?php
					    }				    
					}
					?> 
				</p>
			</div>
			<div class="contenuCommentaire">
				<?php echo $val['commentaire_contenu']; ?>
			</div>
			<?php
			if( isset( $val['signature'] ) && !empty( $val['signature'] ) )
			{
			?>
			<div class="signaturecommentaire_auteur">
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
		if( $member->is_connected() && $this->datas['lectureSeule'] == false )
		{
		?>
		<form method="post" action="">
				<fieldset>
					<legend>Ajouter un commentaire.</legend>
					<p>
					<?php // echo xtcode_form( 'comm_contenu' ); ?>
					<textarea name="comm_contenu" id="comm_contenu" cols="86" rows="10"></textarea>
					<?php // echo previsualiser( 'comm_contenu' ); ?>
					<input type="hidden" name="comm_id" id="comm_id" value="<?php echo $this->datas['id_principal']; ?>" /><br/>
					<input type="submit" name="com_send" id="com_send" />
					</p>
				</fieldset>
		</form>
		<?php
		}
		else
		{
			if( $member->is_connected() ) 
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
        global $rights, $bdd;
        $verif = $bdd->count_sql( $this->table, 'WHERE commentaire_id = ? AND commentaire_auteur = ?', array( $idcom, $_SESSION['__member']['id'] ) );
        //On vérifie si le commentaire existe et si on a les droits pour le modifier.
        if( $verif['nb'] == 0 && ( !$rights->verif( RIGHT_MODIFY ) && !$rights->verif( RIGHT_MODERATE ) ) )
            return false;
        return true;
    }
    
    public function getNbreComs( $module_id )
    {
        global $cache, $bdd;
        $cacheNbComs = $cache->get_infos_cache( 'commentaires/nbComs' . ucfirst( $this->module ) );
        if( empty( $cacheNbComs ) || !isset( $cacheNbComs[$module_id] ) )
	    {
			$nb_coms = $this->refresh_cache( $module_id, $cacheNbComs );
        }
        else
            $nb_coms = $cacheNbComs[$module_id];
        return $nb_coms;
    }
    
    public function refresh_cache( $module_id, $cacheNbComs = false )
    {
		global $bdd, $cache;
		if( !$cacheNbComs || empty( $cacheNbComs ) )
			$cacheNbComs = array();
		//On récupère le nombre de commentaires
		$nb_coms = $bdd->count_sql( $this->table, 'WHERE module_id = ?', $module_id );
		$cacheNbComs[$module_id] = $nb_coms;
		$cache->create_cache( serialize( $cacheNbComs ), 'commentaires/nbComs' . ucfirst( $this->module ) );
		return $nb_coms;
	}
    
    private function pagination( $nb_pages, $page_actuelle = 1 )
    {
        $ecart_pages_membres = self::NB_COMS_PAGE;
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

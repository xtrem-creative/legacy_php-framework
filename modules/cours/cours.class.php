<?php
class Cours
{
	private $id = 0;
	private $membreId = 0;
	
	public function __construct( $id = false, $membreId = false )
	{
		$this->id = ( $id == false ? $id : intval( $id ) );
		$this->membreId = ( $membreId == false ? $membreId : intval( $membreId ) );
	}
	
	private function recupere_cours_par_id( $id, $return = false )
	{
		global $bdd;
		if( isset( $id ) ) $idElement = $id;
		else $idElement = ( isset( $_GET['idElement'] ) ? intval( $_GET['idElement'] ) : 0 );
		$cours = new CoursElement( $idElement );
		if( $return !== false )
			$cours->elementsEnfants();
		else
			return $cours;
	}
	
	private function recupereCoursParMembre( $id, $queTutoriels = true, $return = false )
	{
		global $bdd;
		$typeRequis = ( $queTutoriels != true ? NULL : ' AND cours_type = 1 ' );
		$reqCours = $bdd->query( 'SELECT * FROM ' . TABLE_COURS . ' WHERE cours_auteur = ?' . $typeRequis, $id );
		$coursElements = array();
		while( $dataCours = $bdd->fetch( $reqCours ) )
		{
			$coursElement = new CoursTutoriel( false, $dataCours );
			if( $return != false )
				$coursElement->afficheInline();
			$coursElements[] = $coursElement;
		}
		return $coursElements;
	}
	
	public function racine( $return = false )
	{
		$cours = new CoursElement();
		if( $return !== false )
			$cours->elementsEnfants();
		else
			return $cours;
	}
	
	public function recupereCours()
	{
		return $this->triDifferentesMethodes( false );
	}
	
	public function afficheCours()
	{
		return $this->triDifferentesMethodes( true );
	}
	
	private function triDifferentesMethodes( $return = false )
	{
		if( $this->id !== false )
			return $this->recupereCoursParId( intval( $this->id ), $return );
		elseif( $this->membreId !== 0 )
			return $this->recupereCoursParMembre( intval( $this->membreId ), $return );
		else
			return $this->racine( $return );
	}
	
	public function ajouterElement( $idCategorie, $dataCours, $member )
	{
		global $bdd;
		$dataNiveau = $bdd->requete( 'SELECT cours_level, cours_droite FROM ' . TABLE_COURS . ' WHERE cours_id = ? ', array( $idCategorie ) );
		$bdd->query( 'UPDATE ' . TABLE_COURS . ' SET cours_gauche = cours_gauche + 2 WHERE cours_gauche >= ?', array( $dataNiveau['cours_droite'] ) );
		$bdd->query( 'UPDATE ' . TABLE_COURS . ' SET cours_droite = cours_droite + 2 WHERE cours_droite >= ?', array( $dataNiveau['cours_droite'] ) );
		$bdd->query( 'INSERT INTO ' . TABLE_COURS . ' (cours_nom, cours_level, cours_gauche, cours_droite, cours_auteur, cours_texte, cours_type) 
													VALUES( ?, ?, ?, ?, ?, ?, ? )', array( $dataCours['coursNom'], ( $dataNiveau['cours_level'] + 1),  
													$dataNiveau['cours_droite'], $dataNiveau['cours_droite'] + 1, $member->getId(), '<introduction>' . $dataCours['coursIntro'] . '</introduction><conclusion>' . $dataCours['coursConclusion'] . '</conclusion>', ( isset( $dataCours['isCategory'] ) ? 0 : 1 ) ) );
	}
	
	public function supprimeElement( $dataCours, $member )
	{
		global $bdd;
		$dataNiveau = $bdd->requete( 'SELECT cours_level, cours_droite FROM ' . TABLE_COURS . ' WHERE cours_id = ? ', array( $dataCours['cours_id'] ) );
		$bdd->query( 'DELETE FROM ' . TABLE_COURS . ' WHERE cours_id = ?', array( $dataCours['cours_id'] ) );
		$bdd->query( 'UPDATE ' . TABLE_COURS . ' SET cours_gauche = cours_gauche - 2 WHERE cours_gauche > ?', array( $dataNiveau['cours_droite'] ) );
		$bdd->query( 'UPDATE ' . TABLE_COURS . ' SET cours_droite = cours_droite - 2 WHERE cours_droite > ?', array( $dataNiveau['cours_droite'] ) );
	}
	
	public function modifierElement( $dataCours )
	{
		global $bdd;
		$bdd->query( 'UPDATE ' . TABLE_COURS . ' SET cours_nom = ?, cours_texte = ? WHERE cours_id = ?', array( $dataCours['coursNom'],
		 '<introduction>' . $dataCours['coursIntro'] . '</introduction><conclusion>' . $dataCours['coursConclusion'] . '</conclusion>', $dataCours['cours_id'] ) );
	}
	
	public function deplacerElement( $idNewCategorie, $dataCours, $member )
	{
		global $bdd;
		$dataAncienNiveau = $bdd->requete( 'SELECT cours_level, cours_droite, cours_gauche FROM ' . TABLE_COURS . ' WHERE cours_id = ? ', array( $dataCours['cours_id'] ) );
		$bdd->query( 'UPDATE ' . TABLE_COURS . ' SET cours_gauche = cours_gauche - 2 WHERE cours_gauche >= ?', array( $dataAncienNiveau['cours_droite'] ) );
		$bdd->query( 'UPDATE ' . TABLE_COURS . ' SET cours_droite = cours_droite - 2 WHERE cours_droite >= ?', array( $dataAncienNiveau['cours_droite'] ) );
		$dataNouveauNiveau = $bdd->requete( 'SELECT cours_level, cours_droite FROM ' . TABLE_COURS . ' WHERE cours_id = ? ', array( $idNewCategorie ) );
		$bdd->query( 'UPDATE ' . TABLE_COURS . ' SET cours_gauche = cours_gauche + 2 WHERE cours_gauche >= ?', array( $dataNouveauNiveau['cours_droite'] ) );
		$bdd->query( 'UPDATE ' . TABLE_COURS . ' SET cours_droite = cours_droite + 2 WHERE cours_droite >= ?', array( $dataNouveauNiveau['cours_droite'] ) );
		$bdd->query( 'UPDATE ' . TABLE_COURS . ' SET cours_gauche = ?, cours_droite = ?, cours_level = ? WHERE cours_id = ?', 
				array( $dataNouveauNiveau['cours_droite'], $dataNouveauNiveau['cours_droite'] + 1, $dataNouveauNiveau['cours_level'] + 1, $dataCours['cours_id'] ) );
	}
}

class CoursElement
{
	protected $donnees = array();
	protected $name = NULL;
	protected $id = 0;
	
	protected $type = -1;
	protected $level = 0;
	protected $gauche = 0;
	protected $droite = 0;
	
	protected $estVide = 0;
	
	public function __construct( $id = false, $donneesCours = false )
	{
		$this->id = $id;
		if( $donneesCours !== false ) {
			$this->donnees = $donneesCours;
			$this->assigneDonnees();
		}
		else
			$this->recupereDonnees();
	}
	
	public function affiche()
	{
		
		return <<<EOF
			<h3><a href="index.php?idElement={$this->donnees['cours_id']}">{$this->donnees['cours_nom_secured']}</a></h3>
			<p>{$this->donnees['cours_texte_secured']}</p>
EOF;
	}
	
	public function afficheInline()
	{
		return <<<EOF
			{$this->donnees['cours_nom_secured']}
EOF;
	}
	
	public function afficheElementVide()
	{
		return translate( 'empty_cat' );
	}
	
	public function recupereDonnees()
	{
		global $bdd;
		if( $this->id != 0 )
		{
			$this->donnees = $bdd->requete( 'SELECT * FROM ' . TABLE_COURS . ' WHERE cours_id = ?', $this->id );
			$this->assigneDonnees();
		}
		else
		{
			
		}
	}
	
	private function assigneDonnees()
	{
		$this->donnees['cours_nom_secured'] = htmlspecialchars( $this->donnees['cours_nom'] );
		$this->donnees['cours_texte_secured'] = htmlspecialchars( $this->donnees['cours_texte'] );
		$this->name = $this->donnees['cours_nom'];
		$this->id = intval( $this->donnees['cours_id'] );
		$this->type = intval( $this->donnees['cours_type'] );
		$this->level = intval( $this->donnees['cours_level'] );
		$this->gauche = intval( $this->donnees['cours_gauche'] );
		$this->droite = intval( $this->donnees['cours_droite'] );
	}
	
	public function elementsEnfants()
	{
		global $bdd;
		$this->estVide();
		$this->afficheLienParent();
		if( $this->estVide == 0 )
		{
			echo $this->afficheElementVide();
		}
		else
		{
			if( $this->id == 0 )
				$requeteElementsEnfants = $bdd->query( 'SELECT * FROM ' . TABLE_COURS . ' WHERE cours_level = 0' );
			else
				$requeteElementsEnfants = $bdd->query( 'SELECT * FROM ' . TABLE_COURS . ' WHERE cours_level = ( ? + 1 ) AND cours_gauche > ? AND cours_droite < ? ORDER BY cours_type, cours_gauche', array( $this->level, $this->gauche, $this->droite ) );
			$this->affichageEnfants( $requeteElementsEnfants );
		}
	}
	
	public function elementsEnfantsArborescence( $prefixe = '' )
	{
		global $bdd;
		$this->estVide();
		if( $this->estVide != 0 )
		{
			if( $this->id == 0 )
				$requeteElementsEnfants = $bdd->query( 'SELECT * FROM ' . TABLE_COURS . ' WHERE cours_level = 0' );
			else
				$requeteElementsEnfants = $bdd->query( 'SELECT * FROM ' . TABLE_COURS . ' WHERE cours_level = ( ? + 1 ) AND cours_gauche > ? AND cours_droite < ? ORDER BY cours_type, cours_gauche', array( $this->level, $this->gauche, $this->droite ) );
			$this->affichageArborescence( $requeteElementsEnfants, $prefixe );
		}
	}
	
	public function affichageArborescence( $requeteElementsEnfants, $prefixe = '' )
	{
		global $bdd;
		$presence = array();
		while( $elementEnfant = $bdd->fetch( $requeteElementsEnfants ) )
		{
			switch( $elementEnfant['cours_type'] )
			{
				case 0:
					$coursElement = new CoursCategorie( $elementEnfant['cours_id'] );
				break;
				
				case 1:
					$coursElement = new CoursTutoriel( $elementEnfant['cours_id'] );
				break;
				
				case 2:
					$coursElement = new CoursPartie( $elementEnfant['cours_id'] );
				break;
			}
			if( !empty( $prefixe ) ) $prefixe .= '-';
			echo '<p>' . $prefixe . '> ' . $coursElement->afficheInline() . '</p>';
			$coursElement->elementsEnfantsArborescence();
		}
	}
	
	public function affichageEnfants( $requeteElementsEnfants )
	{
		global $bdd;
		$presence = array();
		while( $elementEnfant = $bdd->fetch( $requeteElementsEnfants ) )
		{
			switch( $elementEnfant['cours_type'] )
			{
				case 0:
					$coursElement = new CoursCategorie( $elementEnfant['cours_id'] );
				break;
				
				case 1:
					$coursElement = new CoursTutoriel( $elementEnfant['cours_id'] );
				break;
				
				case 2:
					$coursElement = new CoursPartie( $elementEnfant['cours_id'] );
				break;
			}
			if( !array_key_exists( $elementEnfant['cours_type'], $presence ) )
			{
				echo '<h2>' . $coursElement->getNom() . '(s)</h2>';
			}
			$presence[$elementEnfant['cours_type']] = true;
			echo $coursElement->affiche();
		}
	}
	
	protected function estVide()
	{
		global $bdd;
		if( $this->id == 0 )
			$this->estVide = $bdd->count_sql( TABLE_COURS );
		else
			$this->estVide = $bdd->count_sql( TABLE_COURS, ' WHERE cours_gauche > ? AND cours_droite < ? AND cours_level = ( ? + 1 )', array( $this->gauche, $this->droite, $this->level ) );
	}
	
	protected function recupereParent()
	{
		global $bdd;
		if( $this->id == 0 )
			return -1;
		else
			return $bdd->requete( 'SELECT cours_id, cours_nom FROM ' . TABLE_COURS . ' WHERE cours_gauche < ? AND cours_droite > ? AND cours_level = ( ? - 1 )', 
											array( $this->gauche, $this->droite, $this->level ) );
	}
	
	protected function afficheLienParent()
	{
		if( ( $donneesCategorieParente = $this->recupereParent() ) !== -1 )
			echo translate( 'back_parent', array( (int)$donneesCategorieParente['cours_id'], ( empty( $donneesCategorieParente['cours_nom'] ) ? 'Racine' : $donneesCategorieParente['cours_nom'] ) ) );
		else
			echo '<p>Aucun lien vers la catégorie parente puisque vous êtes à la racine !</p>';
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getLevel()
	{
		return $this->level;
	}
	
	public function getGauche()
	{
		return $this->gauche;
	}
	
	public function getDroite()
	{
		return $this->droite;
	}
	
	public function getNom()
	{
		return $this->nom;
	}
}

class CoursCategorie extends CoursElement
{
	protected $type = 0;
	protected $nom = 'Catégorie';
}

class CoursTutoriel extends CoursElement
{
	protected $type = 1;
	protected $nom = 'Tutoriel';
	
	public function affiche()
	{
		
		return <<<EOF
			<h3><a href="index.php?afficheTutoriel={$this->donnees['cours_id']}">{$this->donnees['cours_nom_secured']}</a></h3>
			<p>{$this->donnees['cours_texte_secured']}</p>
EOF;
	}
}

class CoursPartie extends CoursElement
{
	protected $type = 2;
	protected $nom = 'Partie';
	
	public function affiche()
	{
		return <<<EOF
			<h3>{$this->donnees['cours_nom_secured']}</h3>
			<p>{$this->donnees['cours_texte_secured']}</p>
EOF;
	}
}
?>

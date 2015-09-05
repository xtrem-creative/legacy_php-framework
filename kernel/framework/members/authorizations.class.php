<?php
/*
 * Class for each right
 * Needs BDD connection
 * */
class Right
{
	private $dataRight = array();
	private $idRight = array();
	
	public function __construct( $idRight, $dataRight = false )
	{
		if( $dataRight === false ) $this->dataRight = self::get_infos( $rightId );
		else $this->dataRight = $dataRight;
		$this->idRight = (int)$idRight;
	}
	
	static function get_infos( $rightId )
	{
		global $bdd;
		$queryRightInfos = $bdd->query( 'SELECT droit_nom, droit_valeur, droit_defaut FROM ' . TABLE_RIGHTS . ' WHERE droit_id = ?', array( $rightId ) );
		$dataRightInfos = $queryRightInfos->fetch( PDO::FETCH_ASSOC );
		if( !empty( $dataRightInfos ) ) return $dataRightInfos;
		else return false;
	}
	
	public function getName()
	{
		return $this->dataRight['droit_nom'];
	}
	
	public function getValue()
	{
		return $this->dataRight['droit_valeur'];
	}
	
	public function getId()
	{
		return $this->dataRight['droit_id'];
	}
	
	public function getDefault()
	{
		return $this->dataRight['droit_defaut'];
	}
}

/*
 * Class for global authorizations
 * Uses right class
 * Needs BDD connection
 * */
class Rights
{
	private $rights = array();
	private $value = 0;
	
	public function __construct( $value = false, $default = false )
	{
		if( $value !== false ) $this->value = $value;
		if( $default !== false ) $this->get_rights_infos_default();
	}
	
	public function get_rights_infos()
	{
		global $bdd;
		$queryRights = $bdd->query( 'SELECT droit_id, droit_nom, droit_valeur, droit_defaut FROM ' . TABLE_RIGHTS );
		while( $dataRight = $queryRights->fetch( PDO::FETCH_ASSOC ) )
		{
			$this->rights[$dataRight['droit_id']] = new Right( $dataRight['droit_id'], $dataRight );
		}
		return $this->rights;
	}
	
	public function get_rights_infos_default()
	{
		global $bdd;
		$queryRights = $bdd->query( 'SELECT droit_id, droit_defaut FROM ' . TABLE_RIGHTS );
		while( $dataRight = $queryRights->fetch( PDO::FETCH_ASSOC ) )
		{
			$this->value += $dataRight['droit_defaut'];
		}
		$this->rights = 0;
		return $this->value;
	}
	
	public function init()
	{
		$this->get_rights_infos();
		foreach( $this->rights AS $idRight => $valRight )
			define( 'RIGHT_' . strtoupper( $valRight->getName() ), serialize( $valRight ) );
	}
	
	public function getRights()
	{
		return $this->value;
	}
	
	public function setRights( $newRights )
	{
		$this->rights = $newRights;
	}
}

/*
 * Class for members' or groups' authorizations.
 * Uses rights class and groups class.
 * */
class Authorizations
{
	protected $ownerId = 0;
	protected $authorizations = array();
	protected $location = array();
	protected $ownerType = 0;
	
	public function __construct( $ownerId, $module = 'accueil', $page = '*' )
	{
		$this->ownerId = intval( $ownerId );
		//Type : member;
		$this->ownerType = 1;
		$this->location = $module . '/' . $page;
		$this->getAuthorizations();
	}
	
	public function getAuthorizations()
	{
		global $bdd;
		$queryAuths = $bdd->query( 'SELECT * FROM ' . TABLE_AUTHS . ' WHERE autorisation_owner = ? AND autorisation_type = ?', array( $this->ownerId, $this->ownerType ) );
		while( $dataAuths = $bdd->fetch( $queryAuths ) )
		{
			$this->authorizations[$dataAuths['autorisation_module'] . '/' . $dataAuths['autorisation_page']] = new Rights( $dataAuths['autorisation_valeur'] );
		}
	}
	
	public function getRights( $location = false )
	{
		if( !$location ) $location = $this->location;
		if( isset( $this->authorizations[$location] ) )
			$toReturn = $this->authorizations[$location]->getRights();
		if( array_key_exists( ( $newLocation = preg_replace( '`([a-zA-B]+)/(.+)`', '$1/*', $location ) ), $this->authorizations ) && ( !isset( $toReturn ) XOR ( array_key_exists( $location, $this->authorizations ) && $this->authorizations[$location]->getRights() == 0 ) ) )
			return $this->authorizations[$newLocation]->getRights();
		elseif( array_key_exists( '*/*', $this->authorizations ) )
			return $this->authorizations[$location]->getRights();
		elseif( isset( $toReturn ) )
			return $toReturn;
		else
			return 0;
	}
	
	public function setRights( $location, $value )
	{
		if( array_key_exists( $location, $this->authorizations ) )
			$this->authorizations[$location]->setRights( $value );
		else
			$this->authorizations[$location] = new Rights( false, true );
	}
}
?>

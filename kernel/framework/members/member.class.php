<?php
load( 'members/authorizations' );
load( 'members/groups' );

class Member
{
    private $design = '';
    private $lang = '';
    private $rank = '';

    private $pseudo = '';
    private $idMember = '';
    private $dataMember = array();

    private $status = '';
    
    public function __construct()
    {
        $this->design = 'base';
        if( isset( $_SESSION['install_lang'] ) )
            $this->lang = $_SESSION['install_lang'];
        else
            $this->lang = DEFAULT_LANG;
        $this->verif_sessions();
        $this->get_data_member();
    }
    
    private function verif_sessions()
    {
        if( isset( $_SESSION['__member']['id'], $_SESSION['__member']['key'], $_SESSION['__member']['pseudo'] ) )
        {
            $verifKey = _hash( $_SESSION['__member']['id'] . $_SESSION['__member']['pseudo'], 'XTC_CMS' );
            if( $verifKey == $_SESSION['__member']['key'] )
            {
                $this->status = 'on';
                $this->pseudo = $_SESSION['__member']['pseudo'];
                $this->idMember = $_SESSION['__member']['id'];
                return true;
            }
        }
        $this->idMember = 0;
        $this->pseudo = NULL;
        $this->status = 'off';
        return false;
    }
    
    public function get_data_member()
    {
        $bdd = new bdd();
        if( $this->idMember == 0 )
			$query = $bdd->query( 'SELECT * FROM  ' . TABLE_MEMBERS . ' WHERE membre_id = 0' );
        else
			$query = $bdd->query( 'SELECT * FROM  ' . TABLE_MEMBERS . ' WHERE membre_id = ?', $this->idMember );
        $fetch = $bdd->fetch( $query );
        $this->dataMember = $fetch;
        $this->lang = ( empty( $fetch['membre_lang'] ) ? DEFAULT_LANG : $fetch['membre_lang'] );
        $this->design = ( empty( $fetch['membre_design'] ) ? DEFAULT_DESIGN : $fetch['membre_design'] );
        $this->rank = ( empty( $fetch['membre_rank'] ) ? RANK_GUEST : $fetch['membre_rank'] );
    }
    
    public function log_in( $idMember )
    {
		$this->idMember = $idMember;
		$this->get_data_member();
		$this->verif_sessions();
		$bdd = new Bdd();
		$bdd->query( 'UPDATE ' . TABLE_MEMBERS . ' SET membre_last_up = ? WHERE membre_id = ?', array( time(), $this->getId() ) );
	}
	
	public function log_out()
    {
		$this->idMember = 0;
		$this->get_data_member();
		$this->verif_sessions();
	}
    
    public function is_connected()
    {
		$this->verif_sessions();
        return ( ( $this->status == 'on' ) ? true : false );
    }
    
    public function verif_rank( $rank )
    {
		if( $this->rank >= $rank )
			return true;
		else
			return false;
	}
    
    public function getDesign()
    {
        return $this->design;
    }
    
    public function getId()
    {
        return (int)$this->idMember;
    }
    
    public function getPseudo()
    {
        return $this->pseudo;
    }
    
    public function getLang()
    {
        return $this->lang;
    } 
    
    public function getRank()
    {
        return $this->rank;
    }    
}

class Member_rights
{
	private $memberId = 0;
	private $groups = array();
	private $authorizations = array();
	private $location = NULL;
	
	public function __construct( $memberId = 0 )
	{
		$this->memberId = (int) $memberId;
	}
	
	public function init( $module = 'accueil', $page = '*' )
	{
		global $bdd;
		$this->location = $module . '/' . $page;
		$this->authorizations = new Authorizations( $this->memberId, $module, $page );
		$groupsQuery = $bdd->query( 'SELECT DISTINCT groupe_id, membre_statut FROM ' . TABLE_MEMBERS_GROUPS . ' WHERE membre_id = ?', array( $this->memberId ) );
		while( $groupsDatas = $bdd->fetch( $groupsQuery ) )
			$this->groups[] = new Group( $groupsDatas['groupe_id'] );
		$this->merge_auths();
	}
	
	public function merge_auths()
	{
		$rightsMember = $this->authorizations->getRights();
		foreach( $this->groups AS $g )
		{
			$rightsGroup = $g->getRights( $this->location );
			$this->authorizations->setRights( $this->location, $this->merge_rights( $rightsGroup, $rightsMember ) );
		}
	}
	
	public function merge_rights( $rights_1, $rights_2 )
	{
		return ( $rights_1 | $rights_2 );
	}
	
	public function compare_rights( $rights_1, $rights_2 )
	{
		return ( $rights_1 & $rights_2 );
	}
	
	public function verif( $rightToCompare, $rights = false, $location = false )
	{
		$rightToCompare = unserialize( $rightToCompare );
		if( !$rights ) $rights = $this->authorizations->getRights( ( $location != false ? false : $location ) );
		return ( ( $rights & $rightToCompare->getValue() ) == 0 ? true : ( $rightToCompare->getDefault() & 1 ? true : false ) );
	}
}
?>

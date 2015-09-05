<?php
class Group extends Authorizations
{
	private $groupName = 0;
	private $groupDatas = array();
	
	public function __construct( $groupId, $groupDatas = false, $module = 'accueil', $page = '*' )
	{
		if( $groupDatas === false ) $this->groupDatas = $this->get_infos( $groupId );
		else $this->groupDatas = $groupDatas;
		$this->ownerId = (int)$groupId;
		$this->ownerType = 2;
		$this->getAuthorizations();
	}
	
	public function get_infos( $groupId )
	{
		global $bdd;
		$queryGroupInfos = $bdd->query( 'SELECT groupe_image, groupe_nbMax, groupe_chefs, groupe_nom FROM ' . TABLE_GROUPS . ' WHERE groupe_id = ?', array( $groupId ) );
		$dataGroupInfos = $bdd->fetch( $queryGroupInfos );
		$this->groupDatas = $dataGroupInfos;
		$this->groupName = $dataGroupInfos['groupe_nom'];
		if( !empty( $dataGroupInfos ) ) return $dataGroupInfos;
		else return false;
	}
	
	public function displays_members()
	{
		global $bdd;
		$queryListMembers = $bdd->query( 'SELECT m.membre_login, gm.membre_statut FROM ' . TABLE_MEMBERS_GROUPS . ' AS gm 
										LEFT JOIN ' . TABLE_MEMBERS . ' AS m ON m.membre_id = gm.membre_id 
										WHERE gm.groupe_id = ? ORDER BY gm.membre_statut, m.membre_login', array( $this->ownerId ) );
		while( $dataListMembers = $bdd->fetch( $queryListMembers ) )
		{
			echoa( $dataListMembers );
		}
	}
}
?>

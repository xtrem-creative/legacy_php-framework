<?php
class Breadcrumb
{
	private $data = array();
	private $links = array();
	private $title = '';
	private $nbres = 0;
	private $plan = NULL;
	
	const SEPARATEUR = ' > ';
	const PLAN_ON = 'on';
	const PLAN_OFF = 'off';
	
	public function __construct( $root = 'home', $lien = ROOTU )
	{
		$this->add( translate( $root ), $lien, 0 );
		$this->nbres++;
		$this->plan = self::PLAN_OFF;
	}
	//Ajoute un élément.
	public function add( $info, $lien = -1, $num = -1, $val = -1 )
	{
		if( $num == -1 )
			$num = $this->refresh();
		++$num;
		$this->data[$num] = $info;
		$this->links[$num] = $lien;
		$this->nbres++;
	}
	
	public function display( $jusque = -1 )
	{
		$nbre_champs = $this->refresh();
		$i = 1;
		$display = '';
		foreach( $this->data AS $key => $info )
		{
			//On récupère les infos correspondantes.
			$link = $this->recup_link( $key );
			$desc = $this->recup_data( $key );
			//Si on n'en est qu'au début + plan.
			if( $i == 1 && $this->plan == self::PLAN_ON )
				$display .= '[ <a href="'.ROOTU.'plan.html">Plan du site</a> ] ';
			//Le lien
			$display .= ( $link == -1 ) ? $desc : '<a href="'.$link.'">'.$desc.'</a>';
			//Si on n'est pas encore arrivé à la fin, on rajoute un séparateur.
			if( $i < $nbre_champs )
				$display .=  self::SEPARATEUR;
			$i++;
		}
		
		return '<p id="breadcrumb">'.$display.'</p>';
	}
	//Remet à jour les variables importantes.
	private function refresh()
	{
		$nbre_1 = sizeof( $this->data );
		$nbre_2 = sizeof( $this->links );
		$this->nbres = ( $nbre_2 > $nbre_1 ) ? $nbre_2 : $nbre_1;
		return $this->nbres;
	}
	
	//Récupère un lien COMPLET (http://....) suivant le nom
	private function recup_link( $num )
	{
		if( is_array( $this->links[$num] ) ) return 'None';
		if( isset( $this->links[$num] ) && $this->links[$num] != -1 )
		{
			if( preg_match( '`^https?://`', $this->links[$num] ) )
				return $this->links[$num];
			else
				return ROOTU . $this->links[$num];
		}
		else
			return -1;
	}
	
	private function recup_data( $num )
	{
		if( isset( $this->data[$num] ) )
		{
			return $this->data[$num];
		}
		else
			return 'Module';
	}
}
?>

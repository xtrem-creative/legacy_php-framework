<?php
class Sessions
{
	public $timeout = 0;
	
    public function __construct()
    {
		if( isset( $_SESSION['timeout'] ) )
			$this->timeout = $_SESSION['timeout'];
		else 
		{
			$_SESSION['timeout'] = time() + SESSIONS_LIMIT;
			$this->timeout = time() + SESSIONS_LIMIT;
		}
		return $this;
    }
    
    public function add_session( $idSession, $valueSession, $module = false )
    {
		if( $module && !isset( $_SESSION[$module][$idSession] ) )
			$_SESSION[$module][$idSession] = $valueSession;
		elseif( $module )
			return $this;
		elseif( !isset( $_SESSION[$idSession] ) )
			$_SESSION[$idSession] = $valueSession;
		return $this;
    }
    
    public function delete_session( $idSession = false, $module = false )
    {
		if( $module === true && isset( $_SESSION[$module][$idSession] ) )
			unset( $_SESSION[$module][$idSession] );
		elseif( isset( $_SESSION[$idSession] ) && $idSession != 'timeout' )
			unset( $_SESSION[$idSession] );
		return $this;
    }
    
    public function update_session( $idSession, $newValue, $module = false )
    {
		if( $module === true && isset( $_SESSION[$module][$idSession] ) )
			$_SESSION[$module][$idSession] = $newValue;
		elseif( isset( $_SESSION[$idSession] ) )
			$_SESSION[$idSession] = $newValue;
		return $this;
	}
	
	public function verif_time_valid()
	{
		if( isset( $_SESSION['timeout'] ) )
			$limit = (int)$_SESSION['timeout'];
		else
			$limit = time() + 10;
		$now = time();
		if( $limit < $now ) 
			$this->end_session();
		return $this;
	}
    
    public function end_session()
    {
		foreach( $_SESSION AS $key => $value )
			unset( $_SESSION[$key] );
		session_destroy();
		return $this;
    }
}
?>

<?php
class Cache
{
    private $dir = '';
    private $data_cache = array();
    
    public function __construct( $dir = DIR_CACHE, $rebuildCache = false )
    {
        $this->dir = ( ( is_dir( $dir ) && $dir ) ? $dir : DIR_CACHE );
        if( $rebuildCache === true )
        {
            $this->rebuild_cache( 'menus' );
            $this->rebuild_cache( 'templates_infos' );
        }
        $this->delete_old_cache();
    }
    
    public function get_cache( $nameCache, $typeExt = CACHE_LOAD, $load = true, $forced = false )
    {
        if( is_file( $this->dir . $nameCache . $typeExt ) && ( CACHE_STATUS == true || $forced === true ) )
            return $this->load( $this->dir . $nameCache . $typeExt, $load, $forced );
        else
            return NULL;
    }
    
    private function load( $file, $load = true, $forced = false )
    {
		if( $load && ( CACHE_STATUS == true || $forced === true ) )
			return require( $file );
		else
			return file_get_contents( $file );
    }
    
    public function rebuild_cache( $name )
    {
        $rc = new ReflectionClass("cache");
        if( $rc->hasMethod('_rebuild_cache_' . $name ) === true )
            eval( '$toReturn = $this->_rebuild_cache_' . $name . '();' );
        else
            $toReturn = false;
        return $toReturn;
    }
    
    public function rebuild_caches()
    {
		$cacheClass = new ReflectionClass('cache');
		$cacheMethods = $cacheClass->getMethods();
		foreach( $cacheMethods AS $method )
		{
			$nameMethod = $method->name;
			if( substr_count( $nameMethod, '_rebuild_cache_' ) && substr_compare( $nameMethod, '_rebuild_cache_', 0, strlen( '_rebuild_cache_' ) ) == 0 )
				eval( '$this->' . $nameMethod . '();' );
		}
	}


	public function get_infos_cache( $name, $serialized = false )
    {
		if( CACHE_STATUS != true ) return NULL;
        if( !isset( $this->data_cache[$name] ) )
        {
            if( !$this->get_cache( $name, false ) && !$serialized )
				$this->create_cache( NULL, $name );
            elseif( !$this->get_cache( $name, false ) )
                $this->create_cache( 'a:0:{}', $name );

			if( !$serialized )
				$this->data_cache[$name] = $this->get_cache( $name, CACHE_LOAD, false );
			else
				$this->data_cache[$name] = unserialize( $this->get_cache( $name ) );
        }
        return $this->data_cache[$name];
    }

    public function create_cache( $content, $name )
	{
		$f = file_put_contents( DIR_CACHE . $name . CACHE_LOAD, $content );
		chmod( DIR_CACHE . $name . CACHE_LOAD, 0777 );
		return $f;
	}
    
    public function delete_cache( $name )
    {
        $dir = DIR_CACHE . $name . CACHE_LOAD;
        if( is_file( $dir ) ) unlink( $dir );
    }

	/*
	 * Peut être utilisée seulement pour les cachs sous forme "serialized".
	 *
	 * */
    public function delete_id_cache( $id, $name )
    {
        $content = unserialize( $this->get_cache( $name ) );
        $dir = DIR_CACHE . $name . CACHE_LOAD;
        if( is_array( $id ) )
        {
            $to_affic = NULL;
            foreach( $id AS $key )
                $to_affic .= "['{$key}']";
            eval( 'if( isset( $content'.$to_affic.' ) ) unset( $content'.$to_affic.' );' );
        }
        elseif( isset( $content[$id] ) )
            unset( $content[$id] );
        $this->create_cache( serialize( $content ), $name );
    }
    
    /*
     * Supprime les fichiers auxquels personne n'a accédé depuis trop longtemps
     * */
    public function delete_old_cache()
    {
		//fileatime
		$dirCacheContent = parcourt_arborescence( ROOT . 'cache', false, true );
		$this->delete_old_cache_dir( $dirCacheContent, ROOT . 'cache' );
	}
	
	private function delete_old_cache_dir( $dirCacheContent, $path = false )
	{
		foreach( $dirCacheContent AS $k => $f )
		{
			if( is_array( $f ) )
				$this->delete_old_cache_dir( $f, $k );
			else
			{
				if( substr_count( $f, CACHE_LOAD ) == 0 ) continue;
				$dif = time() - fileatime( $path . '/' . $f );
				if( $dif > CACHE_LIMIT )
					unlink( $path . '/' . $f );
			}
		}
	}
    
    private function _rebuild_cache_menus()
    {
        $bdd = new Bdd();
        $query = $bdd->query( 'SELECT * FROM ' . TABLE_MENUS . ' ORDER BY menu_order' );
        $linesFile = array( '<?php', '$menus = array();' );
        $i = 0;
        while( $fetch = $bdd->fetch( $query ) )
        {
            $linesFile[] = '$menus[\'' . $fetch['menu_position'] . '\'][' . $i . '] = array( \'label\' => \'' . $fetch['menu_title'] . '\', \'link\' => \'' . $fetch['menu_link'] . '\', \'auths\' => \'' . $fetch['menu_authorizations'] . '\', \'type\' => \'' . $fetch['menu_type'] . '\' );';
            $i++;
        }
        $linesFile[] = 'return $menus;' . "\n" . '?>';
        file_put_contents( DIR_CACHE . 'menus' . CACHE_LOAD, implode( "\n", $linesFile ) );
        chmod( DIR_CACHE . 'menus' . CACHE_LOAD, 0777 );
        return $this->get_cache( 'menus', CACHE_LOAD, true, true );
    }
    
    private function _rebuild_cache_templates_infos()
    {
        $bdd = new Bdd();
        $query = $bdd->query( 'SELECT * FROM ' . TABLE_CONFIG . ' WHERE config_name = "header" OR config_name = "footer"' );
        $linesFile = array( '<?php', '$templates_infos = array();' );
        while( $fetch = $bdd->fetch( $query ) )
        {
            $infosPart = unserialize( $fetch['config_value'] );
            foreach( $infosPart AS $k => $info )
                $linesFile[] = '$templates_infos[\'' . $fetch['config_lang'] . '\'][\'' . $k . '\'] = \'' . $info . '\';';
        }
        $linesFile[] = 'return $templates_infos;' . "\n" . '?>';
        file_put_contents( DIR_CACHE . 'templates_infos' . CACHE_LOAD, implode( "\n", $linesFile ) );
        chmod( DIR_CACHE . 'templates_infos' . CACHE_LOAD, 0777 );
        return $this->get_cache( 'templates_infos', CACHE_LOAD, true, true );
    }
}
?>

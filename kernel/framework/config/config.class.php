<?php
load( 'menus/constructor' );
class Config
{
    private $title = '';
    private $description = '';
    private $copyright = '';
    private $menus = array();
    private $organization = array();
    private $defaultParams = array();
    private $infos = array();
    private $cache = '';
    
    public function __construct( $cache = false, $design = 'base', $lang = 'fr', $loadStructure = true )
    {
		$this->infos = array( 'design' => $design, 'lang' => $lang );
		if( isset( $_SERVER['PHP_SELF'] ) )
			$urlPage = $_SERVER['PHP_SELF'];
		elseif( isset( $_SERVER['SCRIPT_NAME'] ) )
			$urlPage = $_SERVER['PHP_SELF'];
		else
			$urlPage = '/accueil/index.php';
		$elementsUrl = explode( '/', $urlPage );
		$this->infos['module'] = $elementsUrl[count($elementsUrl)-2];
		$this->infos['page'] = basename( $urlPage, '.php' );
        if( !$cache ) $this->cache = NULL;
        else $this->cache = $cache;
        $this->defaultParams['lang'] = DEFAULT_LANG;
        $this->defaultParams['theme'] = DEFAULT_DESIGN;
        $this->defaultParams['module'] = DEFAULT_MODULE;
        if( !is_dir( ROOT . 'designs/' . $this->infos['design'] . '/' ) ) $this->infos['design'] = 'base';
        define( 'DESIGN', ROOTU . 'designs/' . $this->infos['design'] . '/' );
        if( $loadStructure === true ) {
            $iniDesign = ( is_file( ROOT . 'designs/' . $this->infos['design'] . '/design.ini' ) ? get_ini( 'designs/' . $this->infos['design'] . '/design.ini' ) : get_ini( 'designs/base/design.ini' ) );
            foreach( $iniDesign['config']['menus'] AS $nameMenu )
            {
                $menuData = new MenusConstructor( $nameMenu );
                $this->menus[$nameMenu] = $menuData->parse_menus( $menuData->getContent() );
            }

            $this->extract_infos();
            
            $this->organization['footer'] = $iniDesign['config']['footer'];
            $this->organization['header'] = $iniDesign['config']['header'];
        }
    }
    
    private function extract_infos()
    {
        $infos = $this->cache->get_cache( 'templates_infos' );
        if( empty( $infos ) )
        {
            $infos = $this->cache->rebuild_cache( 'templates_infos' );
        }
        $lang = ( !isset( $infos[$this->infos['lang']] ) ? 'fr' : $this->infos['lang'] );
        $this->title = $infos[$lang]['title'];
        $this->description = $infos[$lang]['description'];
        $this->copyright = $infos[$lang]['copyright'];
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getCopyright()
    {
        return $this->copyright;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getMenus()
    {
        return $this->menus;
    }
    
    public function getInfos( $info )
    {
		return ( isset( $this->infos[$info] ) ? $this->infos[$info] : NULL );
	}
    
    public function getDefaultParam( $parameter )
    {
        return ( isset( $this->defaultParams[$parameter] ) ? $this->defaultParams[$parameter] : NULL );
    }
}
?>

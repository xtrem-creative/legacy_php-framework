<?php
class MenusConstructor
{
    private $content = array();
    private $side = '';
    
    const SIDE_LEFT = 'left';
    const SIDE_RIGHT = 'right';    
    const SIDE_TOP = 'top';
    const SIDE_BOTTOM = 'bottom';
    
    public function __construct( $side = self::SIDE_LEFT )
    {
        $this->side = ( in_array( $side, array( self::SIDE_LEFT, self::SIDE_RIGHT, self::SIDE_TOP, self::SIDE_BOTTOM ) ) ? $side : 'left' );
        $this->content = $this->extract();
    }
    
    private function extract()
    {
        global $cache;
        $menus = $cache->get_cache( 'menus' );
        if( empty( $menus ) )
            $menus = $cache->rebuild_cache( 'menus' );
        return ( ( is_array( $menus ) && !empty( $menus ) && array_key_exists( $this->side, $menus ) ) ? $menus[$this->side] : array() );
            
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function parse_menus( $contents = false )
    {
		if( !$contents ) $contents = $this->getContent();
		foreach( $contents AS $k => $content )
		{
			if( !isset( $content['link'] ) )
				continue;
			$linkSplited = str_split( $content['link'] );
			if( $linkSplited[0] == '/' )
				$content['link'] = ROOTU . trim( $content['link'], '/' );
			$contents[$k] = $content;
		}
		return $contents;
	}
}
?>

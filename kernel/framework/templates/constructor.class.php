<?php
load( 'templates/header' );
load( 'templates/menus' );
load( 'templates/footer' );
load( 'templates/other' );
load( 'templates/top' );

class TemplatesConstructor
{
    protected $file = '';
    protected $dirFile = DIR_TEMPLATES;    
    
    protected static $header = '';
    protected static $menus = '';
    protected static $footer = '';
    protected static $top = '';
    
    protected $design = '';
    
    public function construct( $dir = DIR_TEMPLATES )
    {
        $this->dirFile = $dir;
    }
    
    public function init( config $config )
    {
        self::$header = new Header( $config->getTitle(), $config->getDescription(), $config->getInfos( 'design' ) );
        self::$menus = new Menus( $config->getMenus() );
        self::$footer = new Footer( $config->getCopyright() );
        self::$top = new Top();
    }
    
    public function display()
    {
        return $this->dirFile . $this->file . '.tpl';
    }
    
    public function setFile( $newFile, $dir = DIR_TEMPLATES )
    {
        $this->file = $newFile;
        $this->dirFile = $dir;
    }
    
    public function get( $param )
    {
        eval( '$toReturn = $this->' . $param . ';' );
        return $toReturn;
    }
    
    public function header()
    {
        return self::$header;
    }
    
    public function top()
    {
        return self::$top;
    }
    
    public function menus()
    {
        return self::$menus;
    }
    
    public function footer()
    {
        return self::$footer;
    }
}
?>

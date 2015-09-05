<?php
class Header extends TemplatesConstructor
{
    protected $title = '';
    protected $description = '';
    protected $charset = '';
    protected $styles = array();
    
    public function __construct( $title = 'Xtrem CMS', $description = 'Site', $design = '', $charset = 'utf8' )
    {
        $this->file = 'header';
        $this->title = $title;
        $this->description = $description;
        $this->charset = $charset;
        $this->design = $design;
        $this->styles = array( ROOTU . DIR_STYLES . $design . '/' . 'base', ROOTU . DIR_STYLES . $design . '/' . 'design' );
    }
    
    public function add_style_sheet( $name, $dir = DIR_STYLES )
    {
        if( is_file( ROOT . $dir . $name . '.css' ) )
            $this->styles[$dir.$name] = ROOTU . $dir . $name;
		elseif( is_file( ROOT . $dir . $this->design . '/' . $name . '.css' ) )
            $this->styles[$dir.$name] = ROOTU . $dir . $this->design . '/' . $name;
    }
    
    public function setStyles( $newArray )
    {
        $this->styles = $newArray;
    }
	
	public function setTitle( $newTitle )
    {
        $this->title = $newTitle;
    }
}
?>

<?php
class Footer extends TemplatesConstructor
{
    protected $copyright = '';
    protected $menuFooter = NULL;
    
    public function __construct( $copyright = 'Xtrem CMS' )
    {
        $this->file = 'footer';
        $this->copyright = $copyright;
    }
    
    public function add_menu_footer( $menu )
    {
		$this->menuFooter = $menu;
	}
	
	public function getMenuFooter()
    {
		return $this->menuFooter;
	}
}
?>

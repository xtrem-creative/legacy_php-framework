<?php
class Menus extends TemplatesConstructor
{
    protected $menuDatas = '';
	protected $type = '';
	
    public function __construct( $menuDatas = array() )
    {
        $this->file = 'menus';
        $this->menuDatas = $menuDatas;
        $this->type = 1;
    }
    
    public function getType()
    {
		return $this->type;
	}
	
	public function setType( $value )
	{
		$this->type = (int)$value;
	}
}
?>

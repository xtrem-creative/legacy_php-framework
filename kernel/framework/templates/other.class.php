<?php
class OtherTPL extends TemplatesConstructor
{
    protected $datas = array();
    
    public function __construct( $toAddDatas = false )
    {
        if( is_array( $toAddDatas ) )
            $this->datas = $toAddDatas;
        elseif( is_string( $toAddDatas ) )
            $this->datas[] = $toAddDatas;
    }
}
?>

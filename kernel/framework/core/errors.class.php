<?php
class Error
{
    private $issue = '';
    private $type = '';
    
    private static $datas = array();
    
    public function __construct( $issue = false )
    {
        if( !$issue )
            $this->issue = 0;
        else
            $this->issue = (int)$issue;
        return $this;
    }
    
    private function find_type()
    {
        if( in_array( $this->issue, array( ERROR_404, ERROR_403 ) ) )
            $this->type = ERROR_GLOBAL;
        else
            $this->type = ERROR_PAGE;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function add_error( $message, $type = ERROR_PAGE, $page = false, $line = false, $link = false )
    {
        self::$datas[] = array( 'message' => $message, 'type' => ucfirst( $type ), 'page' => $page, 'line' => $line );
        $link = ( !$link ? basename( $page ) : $link );
        if( $type == ERROR_GLOBAL )
        {
			global $tpl;
			$tpl->header()->add_style_sheet( 'errors' );
			$errorPage = new OtherTPL();
			$errorPage->setFile( 'error' );
			require_once( $errorPage->display() );
			exit;
		}
    }
    
    public function getDatas()
    {
        return self::$datas;
    }
}

function error( $label, $type = ERROR_GLOBAL, $file = NULL, $line = NULL, $next = false )//ROOTU . 'modules/accueil/index.php' )
{
	$error = new Error();
	$error->add_error( $label, $type, $file, $line, $next );
}
?>

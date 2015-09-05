<?php
class Lang
{
    private $module = '';
    private $page = '';
    private $lang = '';
    private $dirInclude = '';
    
    private $data = array();
    private $files = array();

    const LANG_DEFAULT = DEFAULT_LANG;

    public function __construct( $module = 'accueil', $page = 'index', $lang = 'fr', $dirInclude = DIR_LANG ) {
        $this->module = $module;
        $this->page = $page;
        $this->lang = $lang;
        $this->dirInclude = $dirInclude;
        $this->extract_lang_files( NULL, NULL, $this->lang );
    }

    public function setPage( $newPage = NULL )
    {
        $this->page = $newPage;
    }
    
    public function setModule( $newModule = NULL, $newPage = NULL )
    {
        $this->module = $newModule;
        if( !empty( $newPage ) ) $this->setPage( $newPage );
    }
    
    public function extract_other_file( $fileName )
    {
        if( !is_file( ROOT . $fileName . LANG_LOAD ) ) return false;
        require_once( ROOT . $fileName . LANG_LOAD );
        if( !empty( $arrayLang ) && is_array( $arrayLang ) )
            foreach( $arrayLang AS $key => $datas )
                $this->data[$module . '_' . $key] = $datas;
    }

    public function get( $index, $page = false, $params = false, $module = false )
    {
        if( !$page )
            $page = $this->page;
        if( !$module )
            $module = $this->module;
        if( !in_array( $module . '_' . $page, $this->files ) && ( $page != 'default' || !in_array( 'default', $this->files ) ) )
			$this->extract_lang_files( $module, $page, $this->lang );
		$keyDefault = $module . '_default';
		if( isset( $this->data[$module . '_' . $page][$index] ) ) {
			$string = $this->data[$module . '_' . $page][$index];
		}
		elseif( isset( $this->data[$module . '_default'][$index] ) ) {
            $string = $this->data[$module . '_default'][$index];
        }
        elseif( isset( $this->data[$keyDefault][$index] ) ) {
			$string = $this->data[$keyDefault][$index];
		}
		elseif( isset( $this->data['default'][$index] ) ) {
			$string = $this->data['default'][$index];
		}
        else
            $string = $index;
        $string = apostrophe( $string, $params );
        return $this->parse_string( $string, $params );
    }
    
    private function parse_string( $string, $params = NULL )
    {
		$toExec = NULL;
		if( is_array( $params ) )
			foreach( $params AS $v )
				$toExec .= ', \'' . $v . '\'';
		else
			$toExec = ', \'' . $params . '\'';
		eval( '$return = sprintf( $string ' . $toExec . ' );' );
		return $return;
	}
    
    private function extract_lang_files( $module, $files = false, $lang = false )
    {
        if( !$lang ) $lang = $this->lang;
        $dir = $this->dirInclude . $lang . '/';
        $dirM = ROOT . 'modules/' . $this->module . '/lang/' . $lang . LANG_LOAD;
        if( is_file( $dir . 'default' . LANG_LOAD ) && !in_array( 'default', $this->files ) ) {
            require_once( $dir . 'default' . LANG_LOAD );
            $this->files[] = 'default';
            $this->data['default'] = $arrayLang['default'];
            unset( $arrayLang );
        }
        if( is_file( $dirM ) ) 
            require_once( $dirM );
        elseif( !is_array( $files ) && is_file( $dir . $files . LANG_LOAD ) )
        {
            if( file_exists( $dir . $files . LANG_LOAD ) )
                require_once( $dir . $files . LANG_LOAD );
            if( !in_array( $files, $this->files ) )
                $this->files[] = $files;
        }
        elseif( is_array( $files ) )
        {
            foreach( $files AS $file ) {
                if( is_file( $dir . $file . LANG_LOAD ) )
                {
                    require_once( $dir . $file . LANG_LOAD );
                    if( !in_array( $file, $this->files ) )
                        $this->files[] = $file;
                }
            }
        }
        if( !empty( $arrayLang ) && is_array( $arrayLang ) )
        {
            foreach( $arrayLang AS $key => $datas )
				$this->data[$this->module . '_' . $key] = $datas;
        }
    }
}
function translate( $index )
{
	$arg_list = func_get_args();
	unset( $arg_list[0] );
    global $lang;
    return $lang->get( $index, false, $arg_list );
}

function plural( $word, $quantity = 1, $plural = false )
{
	if( !$plural )
		$plural = $word . 's';
	if( $quantity > 1 ) return $plural;
	else return $word;
}
/*
 * TODO : Optimiser avec l'aide de preg_match et ce genre de trucs.
 * */
function apostrophe( $string, $params = false )
{
	if( !$params && empty( $params ) ) return $string;
	$pieces = explode( '%', $string );
	//On suppose que la chaîne fournie est valide.
	foreach( $params AS $k => $s )
	{
		$k--;
		if( in_array( $s{0}, array( 'a', 'e', 'i', 'o', 'u', 'h' ) ) )
		{
			$pronounRef = str_split( strrev( $pieces[$k] ) );
			$pronoun = str_split( strtolower( strrev( rtrim( $pieces[$k] ) ) ) );
			$nextPart = ( isset( $pieces[$k+1] ) ? str_split( $pieces[1+$k] ) : 's' );
			if( ( $pronoun[0] == 'e' || $pronoun[0] == 'a' ) && ( $pronoun[1] == 'l' || $pronoun[1] == 'd' ) )
			{
				$diff = 0;
				if( ( $ns2 = sizeof( $pronoun ) ) != ( $ns1 = sizeof( $pronounRef ) ) )
				{
					$diff = $ns1 - $ns2;
					$pronounRef[$diff-1] = '';
				}
				$pronounRef[$diff] = '\'';
				$pieces[$k] = strrev( implode( '', $pronounRef ) );
			}
		}
	}
	return implode( '%', $pieces );
}
?>

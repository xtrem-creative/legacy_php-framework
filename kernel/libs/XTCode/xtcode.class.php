<?php
class XTCode
{
	private $_tags = array();
	private $_smileys = array();
	private $xml_content = NULL;
	
	public function __construct()
	{
		$incs = require( 'xtcode' . INC_LOAD );
		$this->_tags = $incs['balises'];
		$this->_smileys = $incs['smileys'];
	}
	
	public function _from_xtcode_to_xml( $texte )
	{
		$texte = htmlspecialchars( $texte );
		$texte = str_replace( '&quot;', '"', $texte );
		$texte = preg_replace('`\|code(="(.+)")\|(.*)\|/code\|`isU', '<code nom="$2"><![CDATA[$3]]></code>', $texte);
		$texte = preg_replace('`\|minicode(="(.+)")\|(.*)\|/minicode\|`isU', '<minicode nom="$2"><![CDATA[$3]]></minicode>', $texte);
		$texte = nl2br( $texte );
		$texte = str_replace( '<br />', '<saut />', $texte );
		foreach( $this->_tags AS $key => $keyConfig )
		{
			if( substr_count( $texte, $key ) > 0 )
			{
				$nbParams = ( array_key_exists( 'nbParams', $keyConfig ) ? $keyConfig['nbParams'] : 0 );
				$keyConfig['type'] = ( array_key_exists( 'type', $keyConfig ) ? $keyConfig['type'] : 'normale' );
				$keyConfig['name_xml'] = ( array_key_exists( 'name_xml', $keyConfig ) ? $keyConfig['name_xml'] : $key );
				if( $keyConfig['type'] == 'normale' || $keyConfig['type'] == 'image' )
				{
					$toAddXtc = null;
					$toAddXml = null;
					$addI = 0;
					$indicMiddle = 1;
					if( $keyConfig['type'] == 'image' )
						$texte = preg_replace( '`\|image\|(.+)\|/image\|`isU', '<image lien="$1">Image Utilisateur</image>', $texte );
					for( $i = 1; $i <= $nbParams; $i++ )
					{
						$verifP = ( ( array_key_exists( 'choicesParam_' . $i, $keyConfig ) && $keyConfig['choicesParam_' . $i] == true ) ? '(' . $keyConfig['choicesParam_' . $i] . ')' 
						: '(.+)' );
						if( $i == 1 ) 
						{
							if( array_key_exists( 'optionalParam_' . $i, $keyConfig ) ) {
								if( $keyConfig['optionalParam_' . $i] === true )
									$texte = preg_replace( '`\|' . $key . '\|(.+)\|/' . $key . '\|`isU', '<' . $keyConfig['name_xml'] . '>$' . $indicMiddle . '</' . $keyConfig['name_xml'] . '>', $texte );
								elseif( $keyConfig['optionalParam_' . $i] == true && array_search( $keyConfig['optionalParam_' . $i], $keyConfig ) == true )
									$texte = preg_replace( '`\|' . $key . '\|(.+)\|/' . $key . '\|`isU', '<' . $keyConfig['name_xml'] . ' ' . $keyConfig['optionalParam_' . $i] . '="$1">$1</' . $keyConfig['name_xml'] . '>', $texte );
							}
							elseif( array_key_exists( 'defaultXml_' . $i, $keyConfig ) )
								$texte = preg_replace( '`\|' . $key . '\|(.+)\|/' . $key . '\|`isU', '<' . $keyConfig['name_xml'] . ' ' . $keyConfig['paramXml_' . $i] . '="' . $keyConfig['defaultXml_' . $i] . '">$' . $indicMiddle . '</' . $keyConfig['name_xml'] . '>', $texte );
							$toAddXtc .= '="' . $verifP . '"';
							$indicMiddle = 2;
						}
						else
						{
							if( array_key_exists( 'optionalParam_' . $i, $keyConfig ) && $keyConfig['optionalParam_' . $i] === true )
								$texte = preg_replace( '`\|' . $key . $toAddXtc . '\|(.+)\|/' . $key . '\|`isU', '<' . $keyConfig['name_xml'] . $toAddXml . '>$' . $indicMiddle . '</' . $keyConfig['name_xml'] . '>', $texte );
							$toAddXtc .= ' ' . ( isset( $keyConfig['paramXtc_' . $i] ) ? $keyConfig['paramXtc_' . $i] : $keyConfig['paramXml_' . $i] ) . '="' . $verifP . '"';
							$indicMiddle = $nbParams + 1;
						}
						$toAddXml .= ' ' . $keyConfig['paramXml_' . $i] . '="$' . $i . '"';
					}
					$texte = preg_replace( '`\|' . $key . $toAddXtc . '\|(.+)\|/' . $key . '\|`isU', '<' . $keyConfig['name_xml'] . $toAddXml . '>$' . $indicMiddle . '</' . $keyConfig['name_xml'] . '>', $texte );
				}
			}
		}
		load( 'phpmathpublisher/mathpublisher', INC_LOAD );
		$results = array();
		while( preg_match( '`\|math\|(.*)\|/math\|`isU', $texte, $results ) )
		{
			$str = $results[1];
			if( strlen( $str ) > 1000 ) { $texte = str_replace( '|math|'.$str.'|/math|', '', $texte );
			continue; }
			$img = mathfilter( '<m>'.$str.'</m>', 15, ROOTU . 'cache/maths/' );
			$matches = array();
			preg_match( '`"(http://.+)"`isU', $img, $matches );
			echoa( $matches );
			$adresse = trim( $matches[0], '"' );
			$texte = str_replace( '|math|'.$str.'|/math|', '<math lien="'.$adresse.'" form="'.$str.'" />', $texte );
			break;
		}
		#$texte = preg_replace('`<code(\snom="(.+)")>(.*)<br />(.*)</code>`isU', '<code nom="$2">$3$4</code>', $texte );
		#$texte = preg_replace('`<minicode(\snom="(.+)")>((.*)<saut />(.*))*</minicode>`isU', '<minicode nom="$2">$4$5</minicode>', $texte );
#		$texte = preg_replace('`<liste>(<saut />\s)*<puce>`sU', '<liste><puce>', $texte);
#		$texte = preg_replace('`</puce>(<saut />\s)*<puce>`sU', '</puce><puce>', $texte);
#		$texte = preg_replace('`</puce>(<br />\s)*<liste>`sU', '</puce><liste>', $texte);
#		$texte = preg_replace('`</liste>(<br />\s)*</puce>`sU', '</liste></puce>', $texte);
#		$texte = preg_replace('`</puce>(<br />\s)*</liste>`sU', '</puce></liste>', $texte);
		$this->xml_content = $texte;
		return $texte;
	}

	public function _from_xml_to_html( $texte )
	{
		$dom = new DomDocument();
		$texte = '<?xml version="1.0" encoding="UTF8"?>
		<xtcode>' . $texte . '</xtcode>';
		$dom->loadXML( $texte );
		$elements = $dom->getElementsByTagName('xtcode');
		$contenu_xml = $elements->item(0);
		$contenu_parsed = $this->_dom_child_parse( $contenu_xml );
		return $contenu_parsed;
	}
	
	public function _from_xml_to_xtcode( $texte )
	{
		$dom = new DomDocument();
		$texte = '<?xml version="1.0" encoding="UTF8"?>
		<xtcode>' . $texte . '</xtcode>';
		$dom->loadXML( $texte );
		$elements = $dom->getElementsByTagName('xtcode');
		$contenu_xml = $elements->item(0);
		return $this->_dom_child_parse( $contenu_xml, 'xtcode' );
	}
	
	public function _dom_child_parse( $node, $langage = 'html' )
	{
		$content = NULL;
		$child_nodes = $node->childNodes;
		foreach( $child_nodes AS $child_node )
		{
			if( $child_node->hasChildNodes() == true )
				$content .= $this->_dom_child_parse( $child_node, $langage );
			else
				$content .= $this->_dom_tag_parse( $child_node, NULL, $langage );
		}
		return $this->_dom_tag_parse( $node, $content, $langage );
	}
	
	public function _dom_tag_parse( $node, $content_already_parsed = NULL, $parseTo = 'html' )
	{
		static $lastBalise;
		$parseTo = ( $parseTo == 'xtcode' ? 'Xtcode' : 'Html' );
		$name = $node->nodeName;
		if( $name == '#text' )
		{
			if( $parseTo == 'Html' ) {
				$smileys = array_keys( $this->_smileys );
				$pictures = array_values( $this->_smileys );
				foreach( $pictures AS $k => $v )
				{
					$pictures[$k] = '<img src="'.DESIGN.'img/smileys/Tango_Emote_'.$v.'.png" alt="'. $smileys[$k] .'" />';
				}
				$toReturn = str_replace( $smileys, $pictures, $node->nodeValue );
				$toReturn = preg_replace( '`\s(http|ftp|apt)://[a-z0-9._/-]+\s`i', '<a href="$0">$0</a>', $toReturn );
			}
			else
				$toReturn = $node->nodeValue;
			return $toReturn;
		}
		else
		{
			if( $name == 'saut' ) return '<br />';
			if( !array_key_exists( $name, $this->_tags ) ) return $content_already_parsed;
			$toReturn = NULL;
			$nbParams = ( array_key_exists( 'nbParams', $this->_tags[$name] ) ? $this->_tags[$name]['nbParams'] : 0 );
			if( !empty( $content_already_parsed ) )
				$toReturn = $content_already_parsed;
			else
				$toReturn = $node->nodeValue;
			$middle_display = NULL;
			$toAddEnd = NULL;
			$toAddBegin = NULL;
			$needBalise = ( array_key_exists( 'needBalise', $this->_tags[$name] ) ? $this->_tags[$name]['needBalise'] : NULL );
			if( !empty( $needBalise ) && $needBalise != $lastBalise ) return;
			if( $node->hasAttributes() && $nbParams > 0 )
			{
				foreach( $node->attributes AS $attribute )
				{
					$optional = false;
					$defaultValue = NULL;
					if( $name_param = array_search( $attribute->name, $this->_tags[$name], true ) )
					{
						$type = ( array_key_exists( 'type', $this->_tags[$name] ) ? $this->_tags[$name]['type'] : 'normale' );
						$num_param = intval( str_replace( 'paramXml_', '', $name_param ) );
						if( $num_param == NULL ) continue;
						if( array_key_exists( 'optionalParam_' . $num_param, $this->_tags[$name] ) )
							$optional = $this->_tags[$name]['optionalParam_' . $num_param];
						if( array_key_exists( 'defaultXml_' . $num_param, $this->_tags[$name] ) )
							$defaultValue = $this->_tags[$name]['defaultXml_' . $num_param];
						if( array_key_exists( 'choicesParam_' . $num_param, $this->_tags[$name] ) )
						{
							$paramsOkay = explode( '|', $this->_tags[$name]['choicesParam_' . $num_param] );
							if( !in_array( $attribute->value, $paramsOkay ) ) {
								if( $defaultValue && !$optional ) 
									$attribute->value = $defaultValue;
								else
									continue;
							}
						}
						if( array_key_exists( 'paramPlace' . $parseTo . '_' . $num_param, $this->_tags[$name] ) )
							if( $this->_tags[$name]['paramPlace' . $parseTo . '_' . $num_param] == 'end' )
								$toAddEnd = str_replace( "$1", $attribute->value, $this->_tags[$name]['param' . $parseTo . '_' . $num_param] );
							else
								$toAddBegin = str_replace( "$1", $attribute->value, $this->_tags[$name]['param' . $parseTo . '_' . $num_param] );
						else
							$middle_display .= str_replace( "$1", $attribute->value, $this->_tags[$name]['param' . $parseTo . '_' . $num_param] );
						if( $optional !== true && $optional == true && $parseTo == 'Xtcode' )
						{
							$nbAtt = count( $node->attributes );
							if( $attribute->value == $node->nodeValue && $nbAtt == 1 )
								$middle_display = NULL;
						}
					}
				}
			}
			$beginBalise = str_replace( "$1", $middle_display, $this->_tags[$name]['begin' . $parseTo . ''] );
			$lastBalise = $node->nodeName;
			$toReturn = $toAddBegin . $beginBalise . $content_already_parsed . $this->_tags[$name]['end' . $parseTo . ''] . $toAddEnd;
			return $toReturn;
		}
	}
	
	public function getXML()
	{
		return $this->xml_content;
	}
}

function XTCode_encode( $texte )
{
    $xtcode_parseur = new xtcode();
    return $xtcode_parseur->_from_xtcode_to_xml( $texte );
}

function XTCode_decode( $texte )
{
    $xtcode_parseur = new xtcode();
    return $xtcode_parseur->_from_xml_to_html( $texte );
}

function XTCode_xdecode( $texte )
{
	$xtcode_parseur = new xtcode();
    return $xtcode_parseur->_from_xml_to_xtcode( $texte );
}
?>

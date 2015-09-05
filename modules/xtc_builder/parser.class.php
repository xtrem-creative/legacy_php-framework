<?php
class xtc_builder_parser
{
	protected $debutResultat = NULL;
	protected $finResultat = NULL;
	
	protected $content = NULL;
	protected $modules = array();
	
	public function __construct( $content )
	{
		$this->content = $content;
		$this->initialiseContexte();
		$this->initialiseModules();
	}
	
	public function parseContent()
	{
		$return = array();
		$return['beginning'] = $this->debutResultat;
		$lines = explode( "\n", $this->content );
		foreach( $lines AS $nL => $line )
		{
			$oldLine = trim( $line );
			foreach( $this->modules AS $module )
			{
				$line = $module->parseLine( $line );
				if( $line != $oldLine ) break;
			}
			$return[] = ( ( $oldLine == $line ) ? '//' : NULL ) . $line;
		}
		$return['end'] = $this->finResultat;
		return implode( "\n", $return );;
	}
	
	protected function initialiseModules()
	{
	}
	
	protected function initialiseContexte()
	{
		$this->debutResultat = "<?php";
		$this->finResultat = "?>";
	}
}

class xtc_builder_page extends xtc_builder_parser
{
	protected function initialiseModules()
	{
		$this->modules[] = new xtc_builder_module( 'include', 'Include <([^>]+)>', 'include_once( "%s" )' );
		$this->modules[] = new xtc_builder_module( 'load', 'Load <([^>]+)>', 'load( "%s" )' );
		$this->modules[] = new xtc_builder_module_requeteSql( 'requetesql', 'Requete <([^>,]+),([^>,]+),?([^>,]+)?,?([^>,]+)?,?([^>,]+)?,?([^>,]+)?>', '' );
		$this->modules[] = new xtc_builder_module( 'affic_debut', 'AfficDebut', 'tplBegin()' );
		$this->modules[] = new xtc_builder_module( 'affic_fin', 'AfficFin', 'tplEnd()' );
		$this->modules[] = new xtc_builder_module_html( 'html', 'Html', array( 'begin' => '?>', 'end' => '<?php' ), true );
	}
}

class xtc_builder_formulaire extends xtc_builder_parser
{
	protected function initialiseContexte()
	{
		$this->debutResultat = "<?php" . "\n" . '$form = new form();';
		$this->finResultat = "?>";
	}
	
	protected function initialiseModules()
	{
		$this->modules[] = new xtc_builder_module( 'input', 'Input <([^>]+),([^>]+),([^>]+)>', '$form->addInput( "%s", "%s", "%s" )' );
		$this->modules[] = new xtc_builder_module( 'textarea', 'Textarea <([^>]+),([^>]+),([^>]+)>', '$form->addTextarea( "%s", "%s", "%s" )' );
		$this->modules[] = new xtc_builder_module( 'fieldset', 'Fieldset <([^>]+)>', '$form->addFieldset( "%s" )' );
		$this->modules[] = new xtc_builder_module( 'button', 'Button <([^>]+),([^>]+),([^>]+)>', '$form->addButton( "%s" )' );
		$this->modules[] = new xtc_builder_module_liste( 'liste', 'Liste <([^>]+),([^>]+),([^>]+),([^>]+)>', '$liste = $form->addListe( "%s", "%s", "%s", "%s" )', true );
		$this->modules[] = new xtc_builder_module_liste( 'choix', 'Choix <([^>]+),([^>]+)>', '$liste = $form->addListe( "%s", "%s" )', true );
	}
}

class xtc_builder_module
{
	protected $prototype = NULL;
	protected $multiLines = false;
	protected $equivalent = NULL;
	protected $options = array();
	
	public function __construct( $name, $prototype, $equivalent, $multiLines = false )
	{
		$this->name = $name;
		$this->prototype = $prototype;
		$this->equivalent = $equivalent;
		$this->multiLines = $multiLines;
		$this->options['nombresBlocs'] = 0;
	}
	
	public function parseLine( $line, $forceNonMulti = false )
	{
		if( $this->multiLines === true && $forceNonMulti == false )
		{
			if( $line == '{' )
			{
				//On entre dans un bloc.
				$this->options['multi'][++$this->options['nombresBlocs']] = true;
				//On affiche l'info d'entrée s'il y en a une. Sinon un commentaire.
				return ( is_array( $this->equivalent ) && array_key_exists( 'begin', $this->equivalent ) ? $this->equivalent['begin'] : '//-------Début du bloc [' . $this->name . ']-------' );
			}
			elseif( $line == '}' )
			{
				//On en sort.
				unset( $this->options['multi'][$this->options['nombresBlocs']--] );
				//On affiche l'info de sortie s'il y en a une. Sinon rien.
				return ( is_array( $this->equivalent ) && array_key_exists( 'end', $this->equivalent ) ? $this->equivalent['end'] : '//-------Fin du bloc [' . $this->name . ']-------' );
			}
			elseif( $line == $this->prototype )
			{
				return '//------Bloc [' . $this->name . ']-------//';
			}
			elseif( !empty( $this->options['multi'] ) )
			{
				return $this->parseInsideBlock( $line );
			}
		}
		$matches = array();
		$r = preg_match( '`' . $this->prototype . '`is', $line, $matches );
		if( $r === 1 )
		{
			unset( $matches[0] );
			return $this->placeDatas( $matches );
		}
		else
			return trim( $line );
	}
	
	protected function parseInsideBlock( $line )
	{
		return $line;
	}
	
	protected function placeDatas( $matches )
	{
		return vsprintf( $this->equivalent, $matches ) . ';';
	}
}

class xtc_builder_module_html extends xtc_builder_module
{
	protected function parseInsideBlock( $line )
	{
		return "\t" . $line;
	}
}

class xtc_builder_module_liste extends xtc_builder_module
{
	public function parseLine( $line, $forceNonMulti = false )
	{
		$matches = array();
		$r = preg_match( '`' . $this->prototype . '`is', $line, $matches );
		if( $line == '{' )
		{
			$this->options['multi'][++$this->options['nombresBlocs']] = true;
			return ( is_array( $this->equivalent ) && array_key_exists( 'begin', $this->equivalent ) ? $this->equivalent['begin'] : '//-------Début du bloc [' . $this->name . ']-------' );
		}
		elseif( $line == '}' )
		{
			unset( $this->options['multi'][$this->options['nombresBlocs']--] );
			return ( is_array( $this->equivalent ) && array_key_exists( 'end', $this->equivalent ) ? $this->equivalent['end'] : '//-------Fin du bloc [' . $this->name . ']-------' );
		}
		elseif( $r === 1 )
		{
			unset( $matches[0] );
			return $this->placeDatas( $matches );
		}
		elseif( !empty( $this->options['multi'] ) )
		{
			return $this->parseInsideBlock( $line );
		}
		else
			return trim( $line );
	}
	
	protected function parseInsideBlock( $line )
	{
		$matches = array();
		$r = preg_match( '`Option <([^>]+),([^>]+),([^>]+)>`is', $line, $matches );
		if( $r === 1 )
		{
			unset( $matches[0] );
			return '$liste->add( "' . $matches[1] . '", "' . $matches[2] . '", "' . $matches[3] . '" )';
		}
		else
			return trim( $line );
	}
}

class xtc_builder_module_requeteSql extends xtc_builder_module
{
	protected $typesQueries =  array( 'select', 'update', 'delete', 'insert' );
	
	protected function placeDatas( $matches )
	{
		$r = true;
		//On vérifie le type de requête d'abord.
		if( !in_array( $matches[2], $this->typesQueries ) )  $r = NULL;
		$table = strtoupper( $matches[1] );
		//Puis on s'occupe du nom de la table.
		eval( 'if( !defined( \'TABLE_' . $table . '\' ) ) $r = NULL;' );
		//Ensuite, on regarde s'il y a des champs particuliers.
		$champsToSend = 'false';
		if( isset( $matches[3] ) )
		{
			$champsToSend = array();
			$champs = explode( ' ', $matches[3] );
			foreach( $champs AS $champ )
				$champsToSend[] = $champ;
			$champsToSend = ' array( ' . implode( ', ', $champsToSend ) . ' ) ';
		}
		//Puis de même pour les conditions, l'ordre et la limite.
		$conditions = 'false';
		if( isset( $matches[4] ) )
			$conditions = $matches[4];
		$order = 'false';
		if( isset( $matches[5] ) )
			$order = $matches[5];
		$limits = 'false';
		if( isset( $matches[6] ) )
			$limits = str_replace( ' ', ',', $matches[6] );
		if( $r === NULL ) return $r;
		return '$bdd->' . $matches[2] . '( TABLE_' . $table . ', ' . $champsToSend . ', ' . $conditions . ', ' . $order . ', ' . $limits . ' );';
	}
}
?>

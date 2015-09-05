<?php


class FormHandle
{
	private $form = '';
	
	private $sentInfos = array();
	private $okay = false;
	
	public function __construct( form $form )
	{
		$this->form = $form;
		$this->okay = false;
	}
	
	public function handle()
	{
		$toReturn = array();
		$elementsTypes = array( 'input', 'fieldset', 'button', 'textarea', 'list', 'box' );
		foreach( $elementsTypes AS $element )
		{
			$thisElement = $this->form->getElement( $element . 's' );
			foreach( $thisElement AS $v )
			{
				$infos = $v->getInfos();
				if( array_key_exists( 'hasValue', $infos ) )
				{
					$typeValue = array_key_exists( 'typeValue', $infos ) ? $infos['typeValue'] : 'int';
					if( $typeValue == 'disabled' )
						continue;
					elseif( $v->getMultiple() == true ) {
						if( isset( $_POST[$v->getName()] ) )
							foreach( $_POST[$v->getName()] AS $valPostList )
							{
								if( $typeValue == 'str' )
									$toReturn[$v->getName()][] = (string)$valPostList;
								elseif( $typeValue == 'bool' )
									$toReturn[$v->getName()][] = (bool)$valPostList;
								else
									$toReturn[$v->getName()][] = (int)$valPostList;
							}
					}
					elseif( $typeValue == 'str' ) {
						if( isset( $_POST[$v->getName()] ) )
							$toReturn[$v->getName()] = (string)$_POST[$v->getName()];
					} elseif( $typeValue == 'bool' ) {
						if( isset( $_POST[$v->getName()] ) )
							$toReturn[$v->getName()] = (bool)$_POST[$v->getName()];
					}
					elseif( $typeValue == 'file' ) {
						if ( isset( $_FILES[$v->getName()] ) AND $_FILES[$v->getName()]['error'] == 0 ) {
							$if = pathinfo( $_FILES[$v->getName()]['name'] );
							$ext = $if['extension'];
							if( $_FILES[$v->getName()]['size'] <= UPLOAD_MAX_SIZE && in_array( $ext, unserialize( UPLOAD_EXTENSIONS ) ) ) {
								$toReturn[$v->getName()] = $_FILES[$v->getName()]['tmp_name'];
							}
						}
					}
					else { //Cas du int et autres cas
						if( isset( $_POST[$v->getName()] ) )
							$toReturn[$v->getName()] = (int)$_POST[$v->getName()];
					}
					
					if( $v->getNotNull() && isset( $toReturn[$v->getName()] ) && ( ( $v->getNotNull() === 0 && $toReturn[$v->getName()] !== 0 ) || $v->getNotNull() === true ) && empty( $toReturn[$v->getName()] ) )
						unset( $toReturn[$v->getName()] );
					if( $v->getRequired() && ( !isset( $toReturn[$v->getName()] ) && $typeValue != 'bool' ) )
					{
						$toReturn = array();
						break( 2 );
					}
				}
			}
		}
		$this->sentInfos = $toReturn;
		if( !empty( $toReturn ) )
			$this->okay = true;
		return $toReturn;
	}
	
	public function okay()
	{
		return (bool)$this->okay;
	}
	
	public function get( $name )
	{
		return ( array_key_exists( $name, $this->sentInfos ) ? $this->sentInfos[$name] : NULL );
	}
}
?>

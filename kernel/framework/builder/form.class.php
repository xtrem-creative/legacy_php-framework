<?php
load( 'builder/forms/elements' );
load( 'builder/forms/handle' );
class Form
{
    protected $method = '';
    protected $action = '';
    protected $title = '';
    protected $upload = false;
    protected $fieldsets = array();
    protected $inputs = array();
    protected $textareas = array();
    protected $lists = array();
    protected $boxs = array();
    protected $buttons = array();
    protected $nbElements = 0;
    
    public function __construct( $title = NULL, $method = 'post', $action = NULL, $upload = false )
    {
		$this->title = $title;
		$this->method = $method;
		$this->action = $action;
		$this->upload = $upload;
    }
    
    
    
    public function add_input( $name, $id, $label = true, $type = 'text', $typeValue = 'str', $required = true, $notNull = true, $position = false )
    {
		$this->inputs[] = new FormInput( $name, $id, $label, $type, $typeValue, $required, $notNull, $position === false ? count( $this->inputs ) : (int)$position );
		++$this->nbElements;
		return $this->inputs[(count( $this->inputs )-1)];
	}
	
	public function add_textarea( $name, $id, $label = true, $position = false )
    {
		$this->textareas[] = new FormTextarea( $name, $id, $label, $position === false ? count( $this->inputs ) : (int)$position );
		++$this->nbElements;
		return $this->textareas[(count( $this->textareas )-1)];
	}
	
	public function add_fieldset( $title = false, $position = false )
    {
		$this->fieldsets[] = new FormFieldset( ( !$title ? $this->title : $title ), $position === false ? count( $this->fieldsets ) : (int)$position );
		++$this->nbElements;
		return $this->fieldsets[(count( $this->fieldsets )-1)];
	}
	
	public function add_button( $type = 'submit', $name = 'buttonSubmit', $value = 'Envoyer', $position = false )
    {
		$this->buttons[] = new FormButton( $type, $name, $value, $position );
		++$this->nbElements;
		return $this->buttons[(count( $this->buttons )-1)];
	}
    
    public function add_list( $name, $id, $label = true, $typeValue = 'str', $required = true, $notNull = true, $position = false )
    {
		$this->lists[] = new FormList( $name, $id, $label, $typeValue, $required, $notNull, $position === false ? count( $this->inputs ) : (int)$position );
		++$this->nbElements;
		return $this->lists[(count( $this->lists )-1)];
	}
	
	public function add_box( $name, $typeBox, $required = true, $notNull = true, $position = false )
    {
		$this->boxs[] = new FormBox( $name, $typeBox, $required, $notNull, $position === false ? count( $this->inputs ) : (int)$position );
		++$this->nbElements;
		return $this->boxs[(count( $this->boxs )-1)];
	}
	
	public function build_all()
	{
		global $cache;
		$md5 = $this->getMd5();
		$cacheForm = $cache->get_infos_cache( 'forms/form_' . $md5 );
		if( !empty( $cacheForm ) )
		{
			echo $cacheForm;
		}
		else
		{
			ob_start();
			$toReturn = array();
			$elementsTypes = array( 'input', 'fieldset', 'button', 'textarea', 'list', 'box' );
			foreach( $elementsTypes AS $element )
			{
				${$element} = $this->getElement( $element . 's' );
				foreach( ${$element} AS $v )
					$toReturn[$v->getPosition()] = $v->build();
			}
			ksort( $toReturn );
			
			$elementsToClose = array();
			echo "\n" . '<form method="' . $this->method . '" action="' . $this->action . '"' . ( $this->upload == true ? ' enctype="multipart/form-data"' : NULL ) .'>' . "\n";
			if( $this->upload == true )
				echo '<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />' . "\n";
			$inline = false;
			foreach( $toReturn AS $i => $formElement )
			{
				if( isset( $formElement['end'] ) && !empty( $formElement['end'] ) )
				{
					if( isset( $elementsToClose[$formElement['type']] ) )
						echo $elementsToClose[$formElement['type']][1] . "\n";
					$elementsToClose[$formElement['type']] = array( $i, $formElement['end'] );
				}
				
				if( isset( $formElement['inline'] ) && $formElement['inline'] == true ) {
					if( $inline == false ) echo '<p>';
					$inline = true;
				}
				elseif( $inline == true ) {
					echo '</p>';
					$inline = false;
				}
				if( isset( $formElement['inline'] ) && $formElement['inline'] != true )
					echo '<p>' . $formElement['begin'] . "</p>\n";
				else
					echo $formElement['begin'] . "\n";
			}
			$endToReturn = array();
			foreach( $elementsToClose AS $type => $end )
				$endToReturn[$end[0]] = $end[1] . "\n";
			krsort( $endToReturn );
			echo implode( $endToReturn );
			echo '</form>';
			$formToCache = ob_get_contents();
			ob_end_flush();
			$cache->create_cache( $formToCache, 'forms/form_' . $md5 );
		}
	}
	
	
	
	public function getMd5()
	{
		return md5( serialize( array( $this->getTitle(), $this->getAction(), $this->getMethod(), $this->getFieldsets(), $this->getInputs(), $this->getButtons() ) ) );
	}
	
	public function display_top()
	{
		echo "\n" . '<form method="' . $this->method . '" action="' . $this->action . '"' . ( $this->upload == true ? ' enctype="multipart/form-data"' : NULL ) .'>' . "\n";
		if( $this->upload == true )
			echo '<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />' . "\n";
	}
	
	public function display_footer()
	{
		$toReturn = array();
		$elementsTypes = array( 'input', 'fieldset', 'button', 'textarea' );
		foreach( $elementsTypes AS $element )
		{
			${$element} = $this->getElement( $element . 's' );
			foreach( ${$element} AS $v )
				$toReturn[$v->getPosition()] = $v->build();
		}
		ksort( $toReturn );
		
		$elementsToClose = array();
		foreach( $toReturn AS $i => $formElement )
			if( isset( $formElement['end'] ) && !empty( $formElement['end'] ) )
				$elementsToClose[$formElement['type']] = array( $i, $formElement['end'] );
		$endToReturn = array();
		foreach( $elementsToClose AS $type => $end )
			$endToReturn[$end[0]] = $end[1] . "\n";
		krsort( $endToReturn );
		echo implode( $endToReturn );
		echo '</form>';
	}
	
    /*
     * Accesseurs
     * title, action, method
     * */
    public function setTitle( $newTitle )
    {
		$this->title = $newTitle;
	}
	
	public function setAction( $newAction )
    {
		$this->action = $newAction;
	}
	
	public function setMethod( $newMethod )
    {
		$this->method = $newMethod;
	}
	
	/*
     * Getteurs
     * title, action, method
     * */
    public function getTitle()
    {
		return $this->title;
	}
	
	public function getAction()
    {
		return $this->action;
	}
	
	public function getMethod()
    {
		return $this->method;
	}
	
	public function getFieldsets()
    {
		return $this->fieldsets;
	}
	
	public function getFieldset( $id )
	{
		return ( isset( $this->fieldsets[$id] ) ? $this->fieldsets[$id] : false );
	}
	
	public function getInputs()
    {
		return $this->inputs;
	}
	
	public function getInput( $id )
	{
		return ( isset( $this->inputs[$id] ) ? $this->inputs[$id] : false );
	}
	
	public function getLists()
    {
		return $this->lists;
	}
	
	public function getList( $id )
	{
		return ( isset( $this->lists[$id] ) ? $this->lists[$id] : false );
	}
	
	public function getBoxs()
    {
		return $this->boxs;
	}
	
	public function getBox( $id )
	{
		return ( isset( $this->boxs[$id] ) ? $this->boxs[$id] : false );
	}
	
	public function getTextareas()
    {
		return $this->textareas;
	}
	
	public function getTextarea( $id )
	{
		return ( isset( $this->textareas[$id] ) ? $this->textareas[$id] : false );
	}
	
	public function getButtons()
    {
		return $this->buttons;
	}
	
	public function getButton( $id )
	{
		return ( isset( $this->buttons[$id] ) ? $this->buttons[$id] : false );
	}
	
	public function getElement( $element )
	{
		$r = false;
		$rc = new ReflectionClass( $this );
		if( $rc->hasProperty( $element ) === true )
			eval( '$r = $this->get' . ucfirst( $element ) . '();' );
		return $r;
	}
}
?>

<?php
class FormElement
{
	protected $positionRel = 0;
	protected $positionAbs = 0;
	protected $name = NULL;
	protected $infos = array();
	protected $multiple = false;
	protected $notNull = false;
	protected $required = false;
	protected $inline = false;
	
	protected $value = NULL;
	protected $size = NULL;
	
	public static $numElement = array();
	public static $nbElements = 0;
	
	public function set_position( $newPosition )
	{
		$this->position = (int)$newPosition;
	}
	
	protected function extract_position_rel( $type, $position = false )
	{
		if( isset( self::$numElement[$type] ) && $position === false )
			$position = self::$numElement[$type];
		elseif( isset( self::$numElement[$type] ) && $position )
			$position = intval( $position );
		else 
		{
			$position = 0;
			self::$numElement[$type] = 0;
		}
		self::$numElement[$type]++;
		return intval( $position );
	}
	
	protected function extract_position_abs()
	{
		return ++self::$nbElements;
	}
	
	public function build()
	{
		return array( 'begin', 'end', 0 );
	}
	
	public function setValue( $value )
	{
		$this->value = $value;
		return $this;
	}
	
	public function setSize( $value )
	{
		$this->size = intval( $value );
		return $this;
	}
	
	public function setMultiple()
	{
		$this->multiple = !$this->multiple;
		return $this;
	}
	
	public function getMultiple()
	{
		return $this->multiple;
	}
	
	public function getPosition()
	{
		return $this->positionAbs;
	}
	
	public function getInfos()
	{
		return $this->infos;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getNotNull()
	{
		return $this->notNull;
	}
	
	public function getRequired()
	{
		return $this->required;
	}
	
	public function setNotNull( $val )
	{
		$this->notNull = $val;
		return $this;
	}
	
	public function setRequired( $val )
	{
		$this->required = $val;
		return $this;
	}
	
	public function getInline()
	{
		return $this->inline;
	}
	
	public function setInline( $val )
	{
		$this->inline = $val;
		return $this;
	}
	
	public function display()
	{
		ob_start();
		$formElement = $this->build();
		if( isset( $formElement['end'] ) && !empty( $formElement['end'] ) )
		{
			if( isset( $elementsToClose[$formElement['type']] ) )
				echo $elementsToClose[$formElement['type']][1] . "\n";
			$elementsToClose[$formElement['type']] = array( $i, $formElement['end'] );
		}
		echo $formElement['begin'] . "\n";
		$formToCache = ob_get_contents();
		ob_end_flush();
		return $formToCache;
	}
}

class FormFieldset extends FormElement
{
	public function __construct( $name = NULL, $position = false )
	{
		$this->positionRel = $this->extract_position_rel( 'fieldset', $position );
		$this->positionAbs = $this->extract_position_abs( 'fieldset', $position );
		$this->name = $name;
	}
	
	public function build()
	{
		$begin = <<<EOD
	<fieldset>
		<legend>{$this->name}</legend>
EOD;
		if( !empty( $this->value ) )
			$begin .= $this->value;
		$end = <<<EOD
	</fieldset>
EOD;
		return array( 'type' => 'fieldset', 'begin' => $begin, 'end' => $end, 'position' => $this->positionAbs, 'positionRel' => $this->positionRel );
	}
}


class FormInput extends FormElement
{
	private $id = '';
	private $typeInput = '';
	private $typeValue = '';
	private $label = true;
	
	protected $notNull = true;
	
	public function __construct( $name, $id = false, $label = false, $typeInput = 'text', $typeValue = 'str', $required = true, $notNull = true, $position = false )
	{
		$this->typeInput = $typeInput;
		$this->positionRel = $this->extract_position_rel( 'input', $position );
		$this->positionAbs = $this->extract_position_abs( 'input', $position );
		$this->name = $name;
		$this->id = $id;
		$this->notNull = (bool)$notNull;
		$this->required = (bool)$required;
		$this->typeValue = $typeValue;
		$this->label = $label;
		$this->infos = array( 'typeValue' => $this->typeValue, 'hasValue' => true, 'label' => $label );
	}
	
	public function build()
	{
		$value = ( $this->value !== NULL ? ' value="' . $this->value . '"': NULL );
		$disabled = ( ( $this->typeValue == 'disabled' ) ? ' disabled="disabled"' : NULL );
		$disabled = ( ( $this->size !== NULL ) ? ' size="' . $this->size . 'px"' : NULL );
		$this->infos['label'] = ( ( $this->label === true ) ? $this->name : $this->label );
		if( $this->label == true )
		$begin = <<<EOD
		<label for="{$this->id}">{$this->infos['label']} :</label><input type="{$this->typeInput}" name="{$this->name}" id="{$this->id}"{$value}{$disabled} />
EOD;
		else
		$begin = <<<EOD
		<input type="{$this->typeInput}" name="{$this->name}" id="{$this->id}"{$value}{$disabled} />
EOD;
		return array( 'type' => 'input', 'notNull' => $this->notNull, 'typeInput' => $this->typeInput, 'typeValue' => $this->typeValue, 'begin' => $begin, 'end' => NULL, 'position' => $this->positionAbs, 'positionRel' => $this->positionRel, 'hasValue' => true, 'inline' => $this->inline );
	}
}


class formTextarea extends formElement
{
	private $id = '';
	private $label = true;
	
	protected $notNull = true;
	
	public function __construct( $name, $id = false, $label = false, $required = true, $notNull = true, $position = false )
	{
		$this->id = $id;
		$this->positionRel = $this->extract_position_rel( 'input', $position );
		$this->positionAbs = $this->extract_position_abs( 'input', $position );
		$this->name = $name;
		$this->notNull = (bool)$notNull;
		$this->required = (bool)$required;
		$this->label = $label;
		$this->infos = array( 'typeValue' => 'str', 'hasValue' => true, 'label' => $label );
	}
	
	public function build()
	{
		$value = ( $this->value !== NULL ? $this->value : NULL );
		if( $this->label == true )
		$begin = <<<EOD
		<label for="{$this->id}">{$this->infos['label']} :</label><br /><textarea rows="10" cols="100" name="{$this->name}" id="{$this->id}">{$value}</textarea>
EOD;
		else
		$begin = <<<EOD
		<textarea rows="10" cols="100" name="{$this->name}" id="{$this->id}">{$value}</textarea>
EOD;
		return array( 'type' => 'textarea', 'notNull' => $this->notNull, 'typeValue' => 'str', 'begin' => $begin, 'end' => NULL, 'position' => $this->positionAbs, 'positionRel' => $this->positionRel, 'hasValue' => true, 'inline' => $this->inline );
	}
}

class FormButton extends FormElement
{
	private $type = '';
	private $onClick = '';
	
	public function __construct( $type, $name = false, $value = 'Envoyer', $position = false, $onClick = false )
	{
		$this->type = $type;
		if( $name == false )
			$name = mt_rand();
		$this->name = $name;
		$this->onClick = $onClick;
		$this->value = $value;
		$this->positionRel = $this->extract_position_rel( 'input', $position );
		$this->positionAbs = $this->extract_position_abs( 'input', $position );
		$this->infos = array( 'typeValue' => 'str', 'hasValue' => true );
	}
	
	public function build()
	{
		$onClick = ( $this->onClick !== false ? ' onClick="' . $this->onClick . '" ' : '' );
		$begin = <<<EOD
			<input type="{$this->type}" name="{$this->name}" value="{$this->value}"{$onClick} />
EOD;
		return array( 'type' => 'button', 'typeButton' => $this->type, 'begin' => $begin, 'end' => NULL, 'position' => $this->positionAbs, 'positionRel' => $this->positionRel, 'hasValue' => true, 'inline' => $this->inline );
	}
	
	public function getonClick()
	{
		return $this->onClick;
	}
	
	public function setonClick( $value )
	{
		$this->onClick = $value;
		return $this;
	}
}



class FormList extends FormElement
{
	private $id = '';
	private $typeInput = '';
	private $typeValue = '';
	private $label = true;
	private $options = array();
	private $selected = '';
	
	protected $notNull = true;
	
	public function __construct( $name, $id = false, $label = false, $typeValue = 'str', $required = true, $notNull = true, $position = false )
	{
		$this->positionRel = $this->extract_position_rel( 'input', $position );
		$this->positionAbs = $this->extract_position_abs( 'input', $position );
		$this->name = $name;
		$this->id = $id;
		$this->notNull = (bool)$notNull;
		$this->required = (bool)$required;
		$this->typeValue = $typeValue;
		$this->label = $label;
		$this->infos = array( 'typeValue' => $this->typeValue, 'hasValue' => true, 'label' => $label );
	}
	
	public function add( $name, $val, $selected = false )
	{
		if( $selected === true ) $this->selected = $name;
		$this->options[$name] = $val;
		return $this;
	}
	
	public function build()
	{
		$disabled = ( ( $this->typeValue == 'disabled' ) ? ' disabled="disabled"' : NULL );
		if( $this->multiple == true ){
			$multiple = ' multiple="multiple" ';
			$toAdd = '[]';
		}else {
			$toAdd = NULl;
			$multiple = NULL;
		}
		$this->infos['label'] = ( ( $this->label === true ) ? $this->name : $this->label );
		$begin = ( $this->label == true ? '<p><label for="' . $this->id . '">' . $this->infos['label'] . ' :</label><br />' : '<p>' );
		$begin .= '<select name="' . $this->name . $toAdd . '" id="' . $this->id . '"' . $multiple . '>' . "\n";
		foreach( $this->options AS $nameOption => $valOption )
			$begin .= '<option value="' . $valOption . '"' . ( $this->selected == $nameOption ? ' selected="selected"' : NULL ) . '>' . $nameOption . '</option>' . "\n";
		$begin .= '</select></p>' . "\n";
		return ( $this->infos = array( 'type' => 'list', 'multiple' => $this->multiple, 'notNull' => $this->notNull, 'typeInput' => $this->typeInput, 'typeValue' => $this->typeValue, 'begin' => $begin, 'end' => NULL, 'position' => $this->positionAbs, 'positionRel' => $this->positionRel, 'hasValue' => true ) );
	}
}


class FormBox extends FormElement
{
	private $id = '';
	private $label = true;
	private $elements = array();
	
	protected $notNull = true;
	
	public function __construct( $name, $typeBox = 'radio', $required = true, $notNull = true, $position = false )
	{
		$this->multiple = ( $typeBox == 'radio' ? false : true );
		$this->positionRel = $this->extract_position_rel( 'input', $position );
		$this->positionAbs = $this->extract_position_abs( 'input', $position );
		$this->name = $name;
		$this->notNull = (bool)$notNull;
		$this->required = (bool)$required;
		$this->infos = array( 'typeValue' => 'str', 'hasValue' => true );
	}
	
	public function add( $val, $id = false, $label = false, $typeValue = 'str', $disabled = false, $checked = false )
	{
		$this->elements[] = array( 'checked' => $checked, 'value' => $val, 'id' => $id, 'label' => $label, 'typeValue' => $typeValue, 'disabled' => $disabled );
		return $this;
	}
	
	public function build()
	{
		if( $this->multiple == true ){
			$typeCheckBox = 'checkbox';
			$toAdd = '[]';
		}else {
			$toAdd = NULl;
			$typeCheckBox = 'radio';
		}
		$values = array();
		$begin = NULL;
		foreach( $this->elements AS $element )
		{
			$name = $this->name;
			$disabled = ( ( $element['disabled'] == true ) ? ' disabled="disabled"' : NULL );
			if( $this->getInline() == true ) $begin .= '<p>';
			if( $element['label'] == true )
				$begin .= '<label for="' . $element['id'] . '">' . $element['label'] . ' :</label>';
			$begin .= '<input type="' . $typeCheckBox . '" name="' . $name . $toAdd . '" id="' . $element['id'] . '" value="' . $element['value'] . '"' . $disabled . ( $element['checked'] == true ? ' checked="checked"' : NULL ) . ' />';
			if( $this->getInline() == true ) $begin .= '</p>';
			$begin .= "\n";
			$values[$name] = $element['typeValue'];
		}
		return array( 'type' => 'box', 'notNull' => $this->notNull, 'typeInput' => $typeCheckBox, 'typeValue' => $values, 'begin' => $begin, 'end' => NULL, 'position' => $this->positionAbs, 'positionRel' => $this->positionRel, 'hasValue' => true, 'inline' => $this->inline );
	}
}
?>

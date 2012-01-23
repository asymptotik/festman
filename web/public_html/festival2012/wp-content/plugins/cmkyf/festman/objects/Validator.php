<?php
interface IValidator
{
	public function validate($str);
}

class Validator
{
	private $validators = array();
	
	public function validate($str)
	{
		$error_message = '';
		
		for($i = 0; $i < count($this->validators); $i++)
		{
			$error_message = fmAppendLine($error_message, $this->validators[$i]->validate($str));
		}
		
		return $error_message;
	}
	
	public function addValidator(IValidator $validator)
	{
		array_push($this->validators, $validator);
	}
}

class RequiredValidator implements IValidator
{
	private $message = '';
	
    public function __construct($message)
	{
	    $this->message = $message;
	}
	
	public function validate($str)
	{
		$ret = '';
		
		if(isset($str) == false || strlen($str) == 0)
		{
			$ret = $this->message;
		}
		
		return $ret;
	}
}

class MaxLengthValidator implements IValidator
{
    private $max_length; 
	private $message = '';
	
	public function __construct($max_length, $message)
	{
		$this->max_length = $max_length;
	    $this->message = $message;
	}
	
	public function validate($str)
	{
		$ret = '';
		
		if(strlen($str) > $this->max_length)
		{
			$ret = $this->message;
		}
		
		return $ret;
	}
}

class DateTimeValidator implements IValidator
{
    private $regex = '/^\d{2,4}-\d{2}-\d{2} \d{2}\:\d{2}(?::\d{2})$/'; 
	private $message = '';
	
	public function __construct($message)
	{
	    $this->message = $message;
	}
	
	public function validate($str)
	{
		$ret = '';
		
		if(strlen($str) > 0 && preg_match($this->regex, $str) == 0)
		{
			$ret = $this->message;
		}
		
		return $ret;
	}
}
?>
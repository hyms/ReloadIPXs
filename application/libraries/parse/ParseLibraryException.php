<?php
class ParseLibraryException extends CI_Exceptions{
	public function __construct($message , $code = 0, Exception $previous = null) {
		
		//codes are only set by a parse.com error
		if($code != 0){
			$message = "parse.com error: ".$message;
		}
		
		parent::__construct($message, $code, $previous);
	}
	
	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

}



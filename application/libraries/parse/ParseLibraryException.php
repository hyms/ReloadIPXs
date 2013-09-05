<?php
class ParseLibraryException extends Exception{
	public function __construct($message='', $code = 0, Exception $previous = null) {
		
		//codes are only set by a parse.com error
		if($code != 0){
			$message = "parse.com error: ".$message;
		}

		parent::__construct($message, $code, $previous);
	}
	
	public function message($params) {
	
		$message=$params['message']; 
		if($params['code']) $code = $params['code'];else $code = 0;
		$previous = null;
		//codes are only set by a parse.com error
		if($code != 0){
			$message = "parse.com error: ".$message;
		}
	
		//parent::__construct($message, $code, $previous);
	}
	
	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

}


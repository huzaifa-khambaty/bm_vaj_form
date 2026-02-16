<?php
final class Log {
	private $filename;
	
	public function __construct($filename = '') {
        if($filename) {
            $this->filename = $filename;
        } else {
            $this->filename = 'error.log';
        }
	}
	
	public function write($message) {
		$file = DIR_LOGS . $this->filename;
		
		$handle = fopen($file, 'a+'); 
		
		fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");
			
		fclose($handle); 
	}
}
?>
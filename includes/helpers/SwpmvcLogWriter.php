<?php
 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Function to write logs. Need to expand this properly to include other log writing utilities
 * i.e. Configuration Writer, Error Log Writer
 */
class SwpmvcLogWriter {
	
	/**
	 * Static variable to store log file path
	 */
	private $log_file_path = NULL;
	
	public function __construct($log_file_path = null) {
		if(!empty($log_file_path)) {
			$this->log_file_path = $log_file_path;
		} else {
			$this->log_file_path = SWPMVC_PLUGIN_DIR.'storage/logs/application.log';
		}
	}
	
	/**
	 * Function to append string into a logfile. Useful for debugging.
	 * Log File is stored in the <plugin_directory>/storage/logs directory as application.log
	 * 
	 * @param string Requires string value to be written.
	 * @return void
	 */	 
	public function applog($str_val) {
		$log_file = $this->log_file_path;
				
		if(empty($str_val)) {
			$str_val='Empty call (Nothing was provided)';
		}
		
		$str_date_time = date('d-m-Y h:i:s A'); // add timestamp
		$str_val = $str_date_time.' - '.$str_val." \n"; // create newline		
		
		$handle = fopen($log_file, 'a') or die('Cannot open file:  '.$log_file);	
		fwrite($handle, $str_val);
		fclose($handle);
	}
}



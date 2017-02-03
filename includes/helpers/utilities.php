<?php
 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Application's logger. It should always be used for logging any value to the log file.
 * Log file is located at plugin's log folder.
 * 
 * @param $str_val (string) 
 * 		whatever you wish to write in the log file.
 */
function swpmvc_applog( $str_val ) {	
	$logger = new SwpmvcLogWriter();
	$logger->applog($str_val);
	unset($logger);
}

/**
 * Function converts a slug (URL Part) to Application's Controller Class Name.
 * 
 * @param string $current_slug (slug) required field.
 * 
 * @return string
 * 		Returns Application's Controller Class Name 
 */
function fn_slug_to_controller_class( $current_slug = null ) {
	if( is_null( $current_slug ) ) {
		return 'Controller404';
	}
	$class_name = strtolower( trim( $current_slug, '/' ) );
	//replace - or _ with /
	$class_name = str_replace( '-', '/', $class_name );
	$class_name = str_replace( '_', '/', $class_name );		
	//explode the string into array
	$class_name_arr = explode('/', $class_name);
	
	$new_class_name = 'Controller'; //All Controller Classes will have this as prefix
	foreach( $class_name_arr as $name ) {
		$new_class_name .= ucfirst( $name ); //Convert First Character to uppercase
	}
	return $new_class_name;
}


/**
 * Search and Load PHP Files automatically based on classes name
 * Application Classes
 * 	Search in
 * 		plugin directory
 * 		plugin template directory
 * 		includes / classes / controllers / helpers / models / views
 * 		
 */
 function swpmvc_autoload_classes( $class ) {
		
	if($class == 'wp_atom_server' ) {
		return;
	}

	$includes_dir = SWPMVC_PLUGIN_DIR."swpmvc/";
	$recursive_directory_iterator = new RecursiveDirectoryIterator($includes_dir);
	foreach(new RecursiveIteratorIterator($recursive_directory_iterator) as $file)
	{
		$file_found = FALSE; 
		
		$extension = $file->getExtension();
		
		if ( $extension === 'php') {
				
			$file_name = $file->getFilename();			
			$extension_less_file_name = $file->getBasename('.php');
						
			//unset($exploded_file);
			if( $class === $extension_less_file_name ) {
				$file_found = TRUE;
				include_once($file);
				break;
			}	
		}
	}
}


/**
 * Returns the timezone string for a wordpress site, even if it's set to a UTC offset
 *
 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
 * 
 * @return string valid PHP timezone string
 * 
 * @link https://www.skyverge.com/blog/down-the-rabbit-hole-wordpress-and-timezones/
 */ 
function swpmvc_get_wordpress_timezone_string() {	
 
    // if site timezone string exists, return it
    if ( $timezone = get_option( 'timezone_string' ) ) {
    	return $timezone;
    }        
 
    // get UTC offset, if it isn't set then return UTC
    if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) ) {    	
    	return 'UTC';
    }        
 
    // adjust UTC offset from hours to seconds
    $utc_offset *= 3600;
 
    // attempt to guess the timezone string from the UTC offset
    if ( $timezone = timezone_name_from_abbr( '', $utc_offset, 0 ) ) {    	
        return $timezone;
    }
	
    // last try, guess timezone string manually
    $is_dst = date( 'I' );
 
    foreach ( timezone_abbreviations_list() as $abbr ) {
        foreach ( $abbr as $city ) {
            if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset )
                return $city['timezone_id'];
        }
    }
     
    // fallback to UTC
    return 'UTC';
}
<?php
/**
 * Standard Helper Functions
 *
 * @author Chris Cagle <cc@3pcmedia.com>
 */
 

/**
 * Get Template
 *
 * Returns the full site path, including http://
 *
 * @param string $name, filename without .php ext
 * @param string $title, for <title> element
 * @param string $separator, for between sitename and title within <title> 
 * @return string 
 */
function get_template($name, $title='', $separator = '|') {
	ob_start();
	$title = $title .' '.$separator.' '. SITENAME;
	$file = $name . ".php";
	include($file);
	$template = ob_get_contents();
	ob_end_clean(); 
	echo $template;
}


/**
 * Get Site Path
 *
 * Returns the full site path, including http://
 *
 * @return string 
 */
function get_site_path() {
	$path_parts = pathinfo($_SERVER['PHP_SELF']);
	$path_parts = str_replace("/includes", "", $path_parts['dirname']);
	#$fullpath = "http://". $_SERVER['SERVER_NAME'] . $path_parts ."/";	
	$fullpath = "http://". $_SERVER['SERVER_NAME'] . $path_parts;
	return rtrim($fullpath, '/');
}


/**
 * Get Root Path
 *
 * @return string 
 */
function get_root_path() {
  $pos = str_replace('includes','', dirname(__FILE__));
  return $pos;
}


/**
 * Check Radio Element 
 *
 * @param string $i, POST value
 * @param string $m, input element's value
 * @param string $e, return=false, echo=true 
 * @return string 
 */
function check_radio($i,$m,$e=true) {
	if ($i != null) { 
		if ( $i == $m ) { 
			$var = ' checked="checked" '; 
		} else {
			$var = '';
		}
	} else {
		$var = '';	
	}
	if(!$e) {
		return $var;
	} else {
		echo $var;
	}
}


/**
 * Check Radio Array Element 
 *
 * @param string $i, POST value
 * @param string $m, input element's value
 * @param string $e, return=false, echo=true 
 * @return string 
 */
function check_radio_array($i,$m,$e=true) {
	if ($i != null) { 
		if ( in_array( $m, $i )) { 
			$var = ' checked="checked" '; 
		} else {
			$var = '';
		}
	} else {
		$var = '';	
	}
	if(!$e) {
		return $var;
	} else {
		echo $var;
	}
}


/**
 * Check Select Element 
 *
 * @param string $i, POST value
 * @param string $m, input element's value
 * @param string $e, return=false, echo=true 
 * @return string 
 */
function check_select($i,$m,$e=true) {
	if ($i != null) { 
		if ( $i == $m ) { 
			$var = ' selected="selected" '; 
		} else {
			$var = '';
		}
	} else {
		$var = '';	
	}
	if(!$e) {
		return $var;
	} else {
		echo $var;
	}
}

/**
 * Clean Input for use in MySQL Statement 
 *
 * @param string $data
 * @param string $db, for use in a DB, almost always yes
 * @param string $html, for strip_tags
 * @return string 
 */
function clean($data, $db=true, $html=false) {
	
	$data = trim($data);
  if (get_magic_quotes_gpc()) { $data = stripslashes($data); } // if get magic quotes is on, stripslashes
  if ($html) { $data = strip_tags($data); } // no html wanted
	
	if (!$db) { // not used in query (just email or display)
		return $data;
	} elseif ($db) { // used in mysql query
		if (is_numeric($data)) {
			return $data;
		} else {
			$data = mysql_real_escape_string($data);
			return $data;
		}
 }
 
}


/**
 * Filename 
 *
 * Echo filename without .php - usually used in <body> tag
 *
 * @return string 
 */
function page_id() {
	$path = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES);
	$file = basename($path,".php");	
	echo $file;	
}


/**
 * Return Absolute File Path
 *
 * Sanitized PHP_SELF
 *
 * @return string 
 */
function get_filepath() {
	$path = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES);
	return $path;	
}


/**
 * Return the Filename
 *
 * This returns just the filename running the script, with the extention .php
 *
 * @return string
 */
function get_filename() {
	$path = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES);
	$file = basename($path,".php");	
	return $file .'.php';	
}


/**
 * Display Alert Messages
 *
 * Looks for a global variable, and displays any test that variable has
 *
 * @uses $notice, $error, $success
 * @param string $msg, default = null
 * @return string
 */
function display_messages($msg=null) {
	global $success;
	global $error;
	global $notice;

	if ($msg) {
		echo $msg;
		exit();
	}
	if ($notice) {
		echo '<div class="notice topmsg">'. $notice .'</div>';
	}
	if ($error) {
		echo '<div class="error topmsg">'. $error .'</div>';
	}
	if ($success) {
		echo '<div class="success topmsg">'. $success .'</div>';
	}
}


/**
 * Send Mail
 *
 * 64-bit safe
 *
 * @uses FROM_EMAIL_ADDRESS (from config.php)
 * @param string $to
 * @param string $subject
 * @param string $message
 * @return bool
 */
function sendmail($to,$subject,$message) {
	
	if (defined('FROM_EMAIL_ADDRESS')){
  	$fromemail = FROM_EMAIL_ADDRESS; 
  } else {
  	$fromemail = 'noreply@example.com';
  }

	$headers  = "From: ".$fromemail."\r\n";
  $headers .= "Reply-To: ".$fromemail."\r\n";
  $headers .= "Return-Path: ".$fromemail."\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=UTF-8\r\n";
  
  if( mail($to,'=?UTF-8?B?'.base64_encode($subject).'?=',"$message",$headers) ) {
     return true;
  } else {
     return false;
  }
}


/**
 * Attempt Fix URL
 *
 * @param string $i
 * @return string
 */
function fix_url($i) {
	return filter_var($i,FILTER_SANITIZE_URL);
}


/**
 * Attempt Fix Email
 *
 * @param string $i
 * @return string
 */
function fix_email($i) {
	return filter_var($i,FILTER_SANITIZE_EMAIL);
}


/**
 * Validate URL
 *
 * @param string $i
 * @return bool
 */
function validate_url($i) {
	return filter_var($i,FILTER_VALIDATE_URL);
}

/**
 * Validate Email
 *
 * @param string $i
 * @return bool
 */
function validate_email($i) {
	return filter_var($i,FILTER_VALIDATE_EMAIL);
}


/**
 * Echo One of Two Values
 *
 * @param string $post, first priority
 * @param string $db, second priority
 * @return string
 */
function _e ($post=null,$db=null) {
	if($post != '') {
		echo $post;
	} elseif($db != '') {
		echo $db;
	} else {
		echo "";	
	}
}


/**
 * Return One of Two Values
 *
 * @param string $post, first priority
 * @param string $db, second priority
 * @return string
 */
function _r ($post=null,$db=null) {
	if($post != '') {
		return $post;
	} elseif($db != '') {
		return $db;
	} else {
		return "";	
	}
}


/**
 * Hash Your Password
 *
 * @param string $s
 * @return string
 */
function phash($s) {
	$s = sha1($s);
	return $s;
}


/**
 * Generate Random Password
 *
 * @param string $length, default is 8
 * @return string
 */
function createRandomPassword($length = 8) {
  $chars = "Ayz23mFGHBxPQefgnopRScdqrTU4CXYZabstuDEhijkIJKMNVWvw56789";
  srand((double)microtime()*1000000);
  $i = 0;
  $pass = '' ;
  while ($i <= $length) {
      $num = rand() % 33;
      $tmp = substr($chars, $num, 1);
      $pass = $pass . $tmp;
      $i++;
  }
  return $pass;
}


/**
 * Cleans a String for use in URL
 *
 * @param string $text
 * @return string
 */
function clean_url($text)  { 
  if (function_exists('mb_strtolower')) {
     $text = strip_tags(mb_strtolower($text)); 
  } else {
     $text = strip_tags(strtolower($text)); 
  }
  $code_entities_match = array(' ?',' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','='); 
  $code_entities_replace = array('','-','-','','','','','','','','','','','','','','','','','','','','','','',''); 
  $text = str_replace($code_entities_match, $code_entities_replace, $text); 
  $text = urlencode($text);
  $text = str_replace('--', '-', $text);
  $text = str_replace('--', '-', $text); 
  $text = rtrim($text, '-');
  return $text; 
} 

/**
 * Converts UTF8 Chars to Non-Special Chars
 *
 * cleans text you want to turn encode from UTF8
 *
 * @param string $text
 * @param string $from_enc
 * @return string
 */
function to7bit($text,$from_enc) {
  if (function_exists('mb_convert_encoding')) {
  	$text = mb_convert_encoding($text,'HTML-ENTITIES',$from_enc);
  }
  $text = preg_replace(
      array('/&szlig;/','/&(..)lig;/',
           '/&([aouAOU])uml;/','/&(.)[^;]*;/'),
      array('ss',"$1","$1".'e',"$1"),
      $text);
  return $text;
}


/**
 * Convert MySQL Timestamp to PHP Readable
 *
 * syntax: date("F j, Y, g:i a", convert_datetime($data['registered_date']))
 *
 * @param string $str
 * @return string
 */
function convert_datetime($str) {
	list($date, $time) = explode(' ', $str);
	list($year, $month, $day) = explode('-', $date);
	list($hour, $minute, $second) = explode(':', $time);
	$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
	return $timestamp;
}


/**
 * Truncate
 *
 * @param string $text
 * @param string $chars
 * @return string
 */
function trunc($text,$chars=200) { 
	if (strlen($text) >= $chars) {
		$text = $text." "; 
		$text = substr($text,0,$chars); 
		$text = substr($text,0,strrpos($text,' ')); 
		$text = $text."..."; 
	}
	return $text; 
} 


/**
 * Prepare String for MySQL LIKE
 *
 * @param string $q
 * @return string
 */
function cleanLike($q) {
	$text = str_replace('_', '%', clean_url(trim($q)));
	return '%'.$text.'%';
}


/**
 * Cleans Image Name
 *
 * makes lowercase, removes everything except _ . -
 *
 * @param string $text
 * @return string
 */
function clean_img_name($text)  { 
  if (function_exists('mb_strtolower')) {
          $text = strip_tags(mb_strtolower($text)); 
  } else {
          $text = strip_tags(strtolower($text)); 
  }
  $code_entities_match = array(' ?',' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','/','*','+','~','`','='); 
  $code_entities_replace = array('','-','-','','','','','','','','','','','','','','','','','','','','','','',''); 
  $text = str_replace($code_entities_match, $code_entities_replace, $text); 
  $text = urlencode($text);
  $text = str_replace('--','-',$text);
  $text = rtrim($text, "-");
  return $text; 
} 


/**
 * Get Files From Dir 
 *
 * Returns array of files in passed directory
 * maybe use glob($path.'*.*') instead
 *
 * @param string $path
 * @return array
 */
function getFiles($path) {
  $handle = @opendir($path) or die("Unable to open $path");
  $file_arr = array();
  while ($file = readdir($handle)) {
          $file_arr[] = $file;
  }
  closedir($handle);
  return $file_arr;
}


/**
 * Myself 
 *
 * Returns the page itself 
 *
 * @param bool $echo
 * @return string
 */
function myself($echo=true) {
	if ($echo) {
		echo htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES);
	} else {
		return htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES);
	}
}


/**
 * Create ISO Timestamp 
 *
 * If you can use date('c'), use that instead
 *
 * @param string $datetime
 * @return string
 */
function makeIso8601TimeStamp($dateTime) {
    if (!$dateTime) {
        $dateTime = date('Y-m-d H:i:s');
    }
    if (is_numeric(substr($dateTime, 11, 1))) {
        $isoTS = substr($dateTime, 0, 10) ."T".substr($dateTime, 11, 8) ."+00:00";
    } else {
        $isoTS = substr($dateTime, 0, 10);
    }
    return $isoTS;
}


/**
 * Adds Current Class to Navigation 
 *
 * add to page navigation like this: class="<?php currentnav(); ?>"
 *
 * @uses get_filename
 * @param string $currentpage
 * @return string
 */
function currentnav($currentpage) {
	if( (get_filename() == 'index.php') && ($currentpage == 'home') ) {
		echo ' current ';
	} else {
		if(strstr($_SERVER['REQUEST_URI'], $currentpage)) {
			echo ' current ';
		}
	}
}


/**
 * States Dropdown 
 *
 * Required the use of the SQL provided
 *
 * @uses check_select
 * @param string $post, the one to make "selected"
 * @param string $type, by default it shows abbreviations. 'abbrev', 'name' or 'mixed'
 * @return string
 */
function StateDropdown($post=null, $type='abbrev') {
	$states = array(
		array('AK', 'Alaska'),
		array('AL', 'Alabama'),
		array('AR', 'Arkansas'),
		array('AZ', 'Arizona'),
		array('CA', 'California'),
		array('CO', 'Colorado'),
		array('CT', 'Connecticut'),
		array('DC', 'District of Columbia'),
		array('DE', 'Delaware'),
		array('FL', 'Florida'),
		array('GA', 'Georgia'),
		array('HI', 'Hawaii'),
		array('IA', 'Iowa'),
		array('ID', 'Idaho'),
		array('IL', 'Illinois'),
		array('IN', 'Indiana'),
		array('KS', 'Kansas'),
		array('KY', 'Kentucky'),
		array('LA', 'Louisiana'),
		array('MA', 'Massachusetts'),
		array('MD', 'Maryland'),
		array('ME', 'Maine'),
		array('MI', 'Michigan'),
		array('MN', 'Minnesota'),
		array('MO', 'Missouri'),
		array('MS', 'Mississippi'),
		array('MT', 'Montana'),
		array('NC', 'North Carolina'),
		array('ND', 'North Dakota'),
		array('NE', 'Nebraska'),
		array('NH', 'New Hampshire'),
		array('NJ', 'New Jersey'),
		array('NM', 'New Mexico'),
		array('NV', 'Nevada'),
		array('NY', 'New York'),
		array('OH', 'Ohio'),
		array('OK', 'Oklahoma'),
		array('OR', 'Oregon'),
		array('PA', 'Pennsylvania'),
		array('PR', 'Puerto Rico'),
		array('RI', 'Rhode Island'),
		array('SC', 'South Carolina'),
		array('SD', 'South Dakota'),
		array('TN', 'Tennessee'),
		array('TX', 'Texas'),
		array('UT', 'Utah'),
		array('VA', 'Virginia'),
		array('VT', 'Vermont'),
		array('WA', 'Washington'),
		array('WI', 'Wisconsin'),
		array('WV', 'West Virginia'),
		array('WY', 'Wyoming')
	);
	
	$options = '<option value=""></option>';
	
	foreach ($states as $state) {
		if ($type == 'abbrev') {
    	$options .= '<option value="'.$state[0].'" '. check_select($post, $state[0], false) .' >'.$state[0].'</option>'."\n";
    } elseif($type == 'name') {
    	$options .= '<option value="'.$state[1].'" '. check_select($post, $state[1], false) .' >'.$state[1].'</option>'."\n";
    } elseif($type == 'mixed') {
    	$options .= '<option value="'.$state[0].'" '. check_select($post, $state[0], false) .' >'.$state[1].'</option>'."\n";
    }
	}
		
	echo $options;
}


/**
 * Implode to English 
 *
 * Implodes to a string that looks like: blah, blah, blah and/& blah 
 *
 * @uses check_select
 * @param string $post, the one to make "selected"
 * @param string $sep
 * @return string
 */
function ImplodeToEnglish($array, $sep = '&') { 
    # sanity check 
    if (!$array || !count ($array)) {
        return ''; 
		}
		
    # get last element    
    $last = array_pop ($array); 

    # if it was the only element - return it 
    if (!count ($array)) {
        return $last;    
		}
    return implode (', ', $array).' '.$sep.' '.$last; 
} 


/**
 * Safe Add Slashes 
 *
 * @param string $text
 * @return string
 */
function safe_slash_html($text) {
  if (get_magic_quotes_gpc()==0) {
          $text = addslashes(htmlentities($text, ENT_QUOTES, 'UTF-8'));
  } else {
          $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
  }
  return $text;
}


/**
 * Create Cookie 
 *
 * @param string $name
 * @param string $value
 * @return bool
 */
function create_cookie($name, $value) {
  return setcookie($name, $value, time()+(60*60*24*365),'/');   
}


/**
 * Get Cookie Value
 *
 * @uses cookie_check
 * @param string $name
 * @return string
 */
function get_cookie($name) {
  if(cookie_check($name)==TRUE) { 
     return $_COOKIE[$name];
  }
}


/**
 * Cookie Check
 *
 * @param string $name
 * @return bool
 */
function cookie_check($name) {
  if(isset($_COOKIE[$name])) {
    return TRUE;
  }
  else {
    return FALSE;
  }
}


/**
 * SimpleXMLExtended Class
 *
 * Extends the default PHP SimpleXMLElement class by 
 * allowing the addition of cdata
 *
 * @param string $cdata_text
 */
class SimpleXMLExtended extends SimpleXMLElement{   
  public function addCData($cdata_text){   
   $node= dom_import_simplexml($this);   
   $no = $node->ownerDocument;   
   $node->appendChild($no->createCDATASection($cdata_text));   
  } 
} 

/**
 * Get XML
 *
 * Returns array of XML file contents
 *
 * @param string $file
 * @return array
 */
function getXML($file) {
    $xml = file_get_contents($file);
    $data = simplexml_load_string($xml, 'SimpleXMLExtended', LIBXML_NOCDATA);
    return $data;
}


/**
 * XML Save
 *
 * @param object $xml
 * @param string $file Filename that it will be saved as
 * @return bool
 */
function XMLsave($xml, $file) {
  $success = $xml->asXML($file) === TRUE;
  return $success && chmod($file, 0755);
}

/**
 * Clean for JS
 *
 * clean string to be suitable for use in javascript
 *
 * @param string $text
 * @return string
 */
function clean_js($text)  { 
  if (function_exists('mb_strtolower')) {
     $text = strip_tags(mb_strtolower($text)); 
  } else {
     $text = strip_tags(strtolower($text)); 
  }
  $code_entities_match = array(' ?',' ','-','--','&quot;','!','é','@','#','$','%','^','&','*','(',')','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','='); 
  $text = str_replace($code_entities_match, '', $text); 
  $text = urlencode(to7bit($text));
  return str_replace('%','', $text); 
} 


/**
 * Add Trailing Slash
 *
 * adds a trailing slash if needed
 *
 * @param string $path
 * @return string
 */
function tsl($path) {
  if( substr($path, strlen($path) - 1) != '/' ) {
          $path .= '/';
  }
  return $path;
}

/**
 * Debug Array
 *
 * Simple array debugging function
 *
 * @param array $array
 * @return string
 */
function debug($array){
  echo '<pre>';
  var_dump($array);
  print_r($array);
  echo '</pre>';
  exit;
}

/**
 * Body Classes
 *
 * Adds classes to your <body> element to help with styling.
 *
 * @return string
 */
function body_classes() {
	$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']); 
	$klass = null;
	if (preg_match('/windows/', $userAgent))  							$klass .= ' windows '; 
	if (preg_match('/mac/', $userAgent)) 										$klass .= ' mac '; 
	if ((bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad')) 	$klass .= ' ipad '; 
	if ((bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone')) 	$klass .= ' iphone ';
	
	echo $klass;
}

/**
 * Super Unique
 *
 * recursive array unique for multiarrays
 *
 * @param array $array
 * @return array
 */
function super_unique($array){
  $result = array_map("unserialize", array_unique(array_map("serialize", $array)));
  foreach ($result as $key => $value)  {
    if ( is_array($value) )    {
      $result[$key] = super_unique($value);
    }
  }
  return $result;
}

/**
 * Get Domain from URL
 *
 * @param array $url
 * @return string
 */
function getDomain($url){
	$domainName = explode("/",$url);
	if(isset($domainName[2])) return $domainName[2];
	else return NULL;
}
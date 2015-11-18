<?php

/**
 * Error Object
 *
 * @package BluCommerce
 * @subpackage SharedLib
 */
class Error
{
	/**
	 *	Error levels
	 *
	 *	@static
	 *	@access protected
	 *	@var array
	 */	
	protected static $_errorLevels;

	/**
	 *	Contructor
	 *
	 *	@access public
	 */
	public function __construct()
	{
		if (!isset(self::$_errorLevels)) {
			self::$_errorLevels = array(
				16384 => 'E_USER_DEPRECATED',
				8192 => 'E_DEPRECATED',
				4096 => 'E_RECOVERABLE_ERROR',
				2048 => 'E_STRICT',
				1024 => 'E_USER_NOTICE',
				512 => 'E_USER_WARNING',
				256 => 'E_USER_ERROR',
				128 => 'E_COMPILE_WARNING',
				64 => 'E_COMPILE_ERROR',
				32 => 'E_CORE_WARNING',
				16 => 'E_CORE_ERROR',
				8 => 'E_NOTICE',
				4 => 'E_PARSE',
				2 => 'E_WARNING',
				1 => 'E_ERROR'
			);
		}
	}

	/**
	 *	Dying.
	 *
	 *	@access public
	 */
	public function shutdownHandler()
	{
	    if ($error = error_get_last()) {
	        if (isset($error['type']) && ($error['type'] == E_ERROR || $error['type'] == E_PARSE || $error['type'] == E_COMPILE_ERROR)) {
	            @ob_end_clean();

	        	$this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
	        }
	    }
	}

	/**
	 *	Error handler
	 *
	 *	@access public
	 *	@param int Error code
	 *	@param string Message
	 *	@param string Filename
	 *	@param int Line
	 *	@return bool True
	 */
	public function handleError($errno, $errstr, $errfile, $errline)
	{
		// Store info
		$this->errorType = $errno;
		$this->errorMessage = $errstr;
		$this->errorFile = $errfile;
		$this->errorLine = $errline;

		// Handle error codes accordingly
	    switch ($errno) {
	    	case E_USER_ERROR:
		    case E_ERROR:
		    case E_COMPILE_ERROR:
		    case E_PARSE:
		        $this->_handleFatal();
		        break;

		    case E_WARNING:
		    case E_USER_WARNING:
			case E_RECOVERABLE_ERROR:
				$this->_handleWarning();
		        break;
case E_STRICT:
break;
			case E_USER_NOTICE:
		    case E_NOTICE:
		    default:
		    	$this->_handleNotice();
		        break;
	    }

	    // Don't execute PHP internal error handler
	    return true;
	}

	/**
	 *	Fatals, argh, help.
	 *
	 *	@access protected
	 */
	protected function _handleFatal()
	{
        // Fatal error, send 500
       // if (!headers_sent()) {
	    	//header('HTTP/1.1 500 Internal Server Error');
	 //   }
header('HTTP/1.1 200 OK');
	    
	    // Log error
	    $errorLine = $this->_logError(true);

	    // Send for help
	    
	    /*mail('leonz@silvercarrot.com',
	    	'Fatal Error on '.$_SERVER['HTTP_HOST'],
	    	'Oh noes, the following happened:'."\r\n\r\n".
	    	$errorLine[3]."\r\n\r\n".
	    	'Severity: '.$errorLine[2]."\r\n".
	    	'File: '.$errorLine[4]."\r\n".
	    	'Line: '.$errorLine[5]."\r\n\r\n".
	    	'Stack: '.str_replace('>>>', "\r\n      ", $errorLine[6])."\r\n\r\n".
	    	'Visitor ID: '.$errorLine[0]."\r\n".
	    	'URI: '.$errorLine[7]."\r\n",
	    	'From: leon.zhao.workitmom <leonz@silvercarrot.com>');
	    */
	    // Output lies
	    /*echo '
		    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>We\'re currently doing some important updates!</title>
			<style type="text/css">
			<!--
			body {
				font-family: Georgia, "Times New Roman", Times, serif; 
				color:#222;
				padding:80px 40px;
				text-align:center	
			}
			h1 {
				font-size:28px;
				margin-bottom:10px;
				font-style:italic;
			}
			p {
				font-size:17px;
				font-style:italic;
			}
			-->
			</style>
			</head>
			<body>
				<h1>We\'re doing some important updates to our site...</h1>
				<p>It\'s only going to take a minute so please bear with us. We\'ll be back up very, very soon!</p>
			</body>
			</html>';*/
		echo '
		    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>We\'re currently doing some important updates!</title>
			<style type="text/css">
			<!--
			body {
				font-family: Georgia, "Times New Roman", Times, serif; 
				color:#222;
				padding:80px 40px;
				text-align:center	
			}
			h1 {
				font-size:28px;
				margin-bottom:10px;
				font-style:italic;
			}
			p {
				font-size:17px;
				font-style:italic;
			}
			-->
			</style>
			</head>
			<body>
				<h1>We\'re sorry.</h1>
				<p>But the page that you requested is no longer available. <br/>We will take you to our home page in a few seconds or you can click <a href="http://www.workitmom.com">here</a>.</p>
				<div>
				<script language="javascript">
				var i = 5;
				window.onload=redirect;
				function redirect()
				{
				    var time = document.getElementById("time");
				    i--;
				    //time.innerHTML = "You will redirect to search page after "+i+"s.";
				    setTimeout("redirect()",1100);
					if(i<0)
					{
						i = 5;
					}
				    if(i==0)
				    {
						i = 5;
				        location.replace("http://www.workitmom.com");
				    }
				}
				</script>
				<span id="time">
				</span>
				</div>
			</body>
			</html>';

		exit;
	}

	/**
	 *	Warning. Naughty.
	 *
	 *	@access protected
	 */
	protected function _handleWarning()
	{
		$this->_logError();
		//throw new Exception($this->errorType.'|'.$this->errorMessage,69);
	}

	/**
	 *	Notice, not so bad
	 *
	 *	@access protected
	 */
	protected function _handleNotice()
	{
		if (strpos($this->errorMessage, 'Memcache::') !== false
				|| strpos($this->errorMessage, 'Unknown::') !== false) {	// Handle memcached errors and "Unknown" errors (which are probably memcached)
			if (strpos($this->errorMessage,'Failed reading line from stream') !== false) {
				//This error is caused by memcached going away after a successful connection - typically caused by the server going wild.
				throw new Exception("MemcachedException",42);
			}
		}
		
		// Handle unserialize errors
		/*if (strpos($this->errorMessage, 'unserialize') !== false) {	// Handle unserialize errors
			if (strpos($this->errorMessage,'Error at offset 0 of ') !== false) {
				//This error is caused by a failed unserialize. Because we currently store settings partly as plain strings and partly as serialzied data, we need to throw a bespoke exception here.
				throw new Exception("UnserializeException",43);
				return; // We don't want to log these.
			}
		}*/
		
		$this->_logError();
	}
	
	/**
	 *	Handle exceptions
	 *
	 *	@static
	 *	@access public
	 *	@param Exception 
	 */
	public static function handleException(Exception $exception)
	{
		// Get info
		$type = get_class($exception);
		$message = $exception->getMessage();
		$file = $exception->getFile();
		$line = $exception->getLine();
		$trace = array();
		foreach ($exception->getTrace() as $traceLine) {
			$trace[] = $traceLine['class'].$traceLine['type'].$traceLine['function'].' ('.$traceLine['line'].')';
		}
		$trace = implode('<br />', $trace);
		
		// Spill info
		if (DEBUG_INFO) {
			self::_displayError($type, $message, $file, $line, $trace);
		}
	}

	/**
	 *	Log error or flush to screen
	 *
	 *	@access protected
	 */
	protected function _logError()
	{
		static $logFile = false;
		static $socket= false;	

		$backtrace = debug_backtrace();
		$requestId = isset($_SERVER['UNIQUE_ID']) ? $_SERVER['UNIQUE_ID'] : null;
		$requestUri = $_SERVER['REQUEST_URI'];

		// Build error path trace lines
		foreach ($backtrace as $errorPath) {
			$errorClass = isset($errorPath['class']) ? $errorPath['class'] : '';
			$errorType = isset($errorPath['type']) ? $errorPath['type'] : '';
			$errorFunction = $errorPath['function'];
			$errorLine = isset($errorPath['line']) ? $errorPath['line'] : 'UNKNOWN';
			$errorLines[] = $errorClass.$errorType.$errorFunction.' ('.$errorLine.') ';
		}
		$errorLines = array_reverse($errorLines);

		// Always log errors with GMT timestap
		date_default_timezone_set('America/New_York');
		
		// Build output for CSV/return
		$backTraceLine = implode('>>> ', $errorLines);
		$line = array(
			$requestId,
			date('r'),
			self::$_errorLevels[$this->errorType],
			$this->errorMessage,
			$this->errorFile,
			$this->errorLine,
			$backTraceLine,
			$requestUri
			/*print_r($errorPath['args'],true),
			$traceObject*/
		);

		$humanErrorString = '#WIM Error ';
		$errorType = self::$_errorLevels[$this->errorType];
		$humanErrorString .=
			'=============='.date('r').'=============='."\n".
			'WIM  threw an error of type '.$errorType.': '.$this->errorMessage."\n".
			$this->errorFile." (".$this->errorLine.")\n".
			$backTraceLine.
			"\nhttp://".$_SERVER['SERVER_NAME'].$requestUri."\n";

		// Output to CSV error log
		//if (DEBUG) {
			//if ($socket == false) {
            //    		$socket = fsockopen("udp://maintenance.blubolt.com", 22727, $errno, $errstr, 1);
        	//        }

            //            fwrite ($socket, $humanErrorString);
	
			// It's been a really early error - log it elsewhere
	
			// Output
//			fputcsv($logFile, $line, '|', '"');
		//	$fp = fopen('errorLog.csv', 'a');
		//	fputcsv($fp, $line);
		//	fclose($fp);
		// Output to screen in debug mode
		/*
			} else if (DEBUG_INFO) {
			
			$type = self::$_errorLevels[$this->errorType];
			$message = $this->errorMessage;
			$file = $this->errorFile;
			$line = $this->errorLine;
			$traceString = implode('<br />', $errorLines);
			
			self::_displayError($type, $message, $file, $line, $traceString);
		}
		*/
		            //if($_SERVER["REMOTE_ADDR"] == "127.0.0.1")
            if($_SERVER["REMOTE_ADDR"] == "216.180.167.121")
            {
                $this->_saveError($errorLines);
                /*
                $file = dirname(__FILE__) . '/errorLog.csv';
                $fp = fopen($file, 'a');
                //fputcsv($fp, $line);
		fwrite($fp, $humanErrorString);
                fclose($fp);
		*/
		/*	
                $type = self::$_errorLevels[$this->errorType];
                $message = $this->errorMessage;
                $file = $this->errorFile;
                $line = $this->errorLine;
                $traceString = implode('<br />', $errorLines);
		self::_displayError($type, $message, $file, $line, $traceString);            
               */
            }
		return $line;
	}
	
	/**
	 *	Output info to screen
	 *
	 *	@static
	 *	@access protected
	 *	@param string Type
	 *	@param string Message
	 *	@param string File
	 *	@param int Line number
	 *	@param string Call trace
	 */
	protected static function _displayError($type, $message, $file, $line, $trace)
	{
		echo '<table style="border-collapse: collapse;">
			<thead>
				<tr>
					<th style="padding: 8px 5px; border: 1px solid #e98400; background: #ffae00; color: #000; font-family: Arial; font-size: 14px; font-weight: normal;">
						<div style="padding: 0; margin: 0 0 5px 0; font-family: Arial;">
							'.$type.': <strong style="font-family: Arial;">'.htmlentities($message).'</strong>
						</div>
						'.$file.' ('.$line.')
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="padding: 5px; border: 1px solid #e98400; background: #fff; color: #000; font-family: Arial; font-size: 12px;">
						'.$trace.'
					</td>
				</tr>
			</tbody>
		</table>';
	}

     /**
     *    Save info into the database (errorLog)
     *
     *    @access protected
     *    @param string Type
     *    @param string Message
     *    @param string File
     *    @param int Line number
     *    @param string Call trace
     */    
     protected function _saveError($errorLines)
     {
        //date_default_timezone_set('ASIA/SHANGHAI');
        //global $timeStartLog;
        $data = array();

        $data['level'] = self::$_errorLevels[$this->errorType];
        $data['url'] = $_SERVER['REQUEST_URI'];
        $data['message'] = $this->errorMessage;
        $data['file'] = $this->errorFile;
        $data['line'] = $this->errorLine;
        $data['time'] = date('Y-m-d H:i:s', microtime(true));
        $data['exceptions'] = implode('<br />', $errorLines);
        $data['notes'] = '';
        
        $sql = "INSERT INTO `errorLog` (`id`, `level`, `url`, `file`, `message`, `line`, `time`, `exceptions`, `notes`)
            VALUES (
            'NULL',
            '" . $data['level'] . "', 
            '" . $data['url'] . "',
            '" . $data['file'] . "', 
            '" . $data['message'] . "', 
            '" . $data['line'] . "',
            '" . $data['time'] . "',
            '" . $data['exceptions'] . "', 
            '" . $data['notes'] . "'
            )";
        mysql_query($sql);         
     }
}
?>

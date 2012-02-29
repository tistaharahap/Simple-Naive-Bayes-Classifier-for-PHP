<?php
/**
 * Custom Exceptions with Custom Codes & Messages
 * 
 * @package	Simple NaiveBayesClassifier for PHP
 * @subpackage	NaiveBayesClassifierException
 * @author	Batista R. Harahap <batista@bango29.com>
 * @link	http://www.bango29.com
 * @license	MIT License - http://www.opensource.org/licenses/mit-license.php
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a 
 * copy of this software and associated documentation files (the "Software"), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in 
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS 
 * IN THE SOFTWARE.
 */

class NaiveBayesClassifierException extends Exception {
	
	function __construct($code = NULL, $else = NULL) {
		switch($code) {
			case 1001:
				parent::__construct('Empty configuration array for NaiveBayesClassifier constructor', $code);
				break;
			case 1002:
				parent::__construct('Store must be defined for NaiveBayesClassifier constructor', $code);
				break;
			case 1003:
				parent::__construct('Store Mode must be defined for NaiveBayesClassifier constructor', $code);
				break;
			case 1004:
				parent::__construct('Store DB Credentials must be defined for NaiveBayesClassifier constructor', $code);
				break;
				
			case 3001:
				parent::__construct('Empty configuration array for NBCStore', $code);
				break;
			
			case 3101:
				parent::__construct('NaiveBayesClassifierStoreMySQL needs a database host defined in the constructor', $code);
				break;
			case 3102:
				parent::__construct('NaiveBayesClassifierStoreMySQL needs a database port defined in the constructor', $code);
				break;
			case 3103:
				parent::__construct('NaiveBayesClassifierStoreMySQL needs a database name defined in the constructor', $code);
				break;
			case 3104:
				parent::__construct('NaiveBayesClassifierStoreMySQL needs a database user defined in the constructor', $code);
				break;
			case 3105:
				parent::__construct('NaiveBayesClassifierStoreMySQL needs a database password defined in the constructor', $code);
				break;
			case 3106:
				parent::__construct('Cannot connect to MySQL Host: ' . mysql_error(), $code);
				break;
			case 3107:
				parent::__construct('Cannot connect to MySQL Database', $code);
				break;
			
			case 3200:
				parent::__construct('HandlerSocket Extension for PHP not installed. Refer to http://code.google.com/p/php-handlersocket/ please.', $code);
				break;
			case 3201:
				parent::__construct('Cannot open specified Index', $code);
				break;
			case 3202:
				parent::__construct('NaiveBayesClassifierStoreHandlerSocket needs a database read port defined in the constructor', $code);
				break;
			case 3203:
				parent::__construct('NaiveBayesClassifierStoreHandlerSocket needs a database write port defined in the constructor', $code);
				break;
			case 3204:
				parent::__construct('NaiveBayesClassifierStoreHandlerSocket needs a database name defined in the constructor', $code);
				break;
			case 3205:
				parent::__construct('Opening Index for HandlerSocket failed, Table Select Fields must be an array', $code);
				break;
			
			default:
				parent::__construct('Uncategorized NaiveBayesClassifierException thrown, check Code Trace please');
		}
	}
	
}
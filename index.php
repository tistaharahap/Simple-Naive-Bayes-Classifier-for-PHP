<?php
/**
 * CLI Implementation for NaiveBayesClassifier project
 * 
 * @package	Simple NaiveBayesClassifier for PHP
 * @subpackage	CLI Runner - Implementation
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

require_once 'NaiveBayesClassifier.php';

$_start = microtime(TRUE);

$nbc = new NaiveBayesClassifier(array(
	'store' => array(
		'mode'	=> 'mysql',
		'db'	=> array(
			'db_host'	=> 'localhost',
			'db_port'	=> '3306',
			'db_name'	=> 'bayes',
			'db_user'	=> 'root',
			'db_pass'	=> '',
			'db_persist'	=> FALSE
		)
	),
	'debug' => TRUE
));

$nbc->train('optimize', 'tista');
$nbc->train('hardware', 'tista');
$nbc->train('web', 'tista');
$nbc->train('software', 'tista');
$nbc->train('naive bayesian classifier', 'tista');
$nbc->train('bayes', 'tista');
$nbc->train('algorithm', 'tista');
$nbc->train('json', 'tista');
$nbc->train('android', 'tista');
$nbc->train('internet', 'tista');
$nbc->train('intranet', 'tista');
$nbc->train('application', 'tista');
$nbc->train('native', 'tista');
$nbc->train('ios', 'tista');
$nbc->train('iphone', 'tista');
$nbc->train('ipad', 'tista');
$nbc->train('programming', 'tista');

$nbc->train('waku', 'arie');
$nbc->train('object oriented', 'arie');
$nbc->train('backend', 'arie');
$nbc->train('service', 'arie');
$nbc->train('architect', 'arie');
$nbc->train('php', 'arie');
$nbc->train('mysql', 'arie');
$nbc->train('mongodb', 'arie');
$nbc->train('programming', 'arie');

$nbc->classify('programming php');

$_end = microtime(TRUE);

echo "TIME Spent: ", ($_end - $_start), " seconds", PHP_EOL, PHP_EOL;
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

ini_set('memory_limit','512M');

require_once 'NaiveBayesClassifier.php';

$nbc = new NaiveBayesClassifier(array(
	'store' => array(
		'mode'	=> 'redis',
		'db'	=> array(
			'db_host'	=> '127.0.0.1',
			'db_port'	=> '6379',
			'namespace'	=> 'reviews'	// Added to differentiate multiple trainsets
		)
	),
	'debug' => FALSE
));

/*echo "Training started.".PHP_EOL;
$_s = microtime(TRUE);

$urb = mysql_connect('127.0.0.1', 'root', '');
mysql_select_db('bayes');

$sql = "SELECT * FROM reviews";
$q = mysql_query($sql);
while($row = mysql_fetch_object($q)) {
	$nbc->train($row->review_text, $row->review_by);
}

mysql_close($urb);

$_e = microtime(TRUE);
$_t = $_e - $_s;
echo "Training finished. Took {$_t} seconds.".PHP_EOL;*/

$_start = 0;
if(!empty($argv) && count($argv) > 1) {
	$words = "";
	for($i=1, $max=count($argv); $i<$max; $i++) {
		$words .= $argv[$i] . " ";
	}
	echo "Classifier started.".PHP_EOL;
	$_start = microtime(TRUE);

	$offset = 0;
	$row = 10;
	$result = $nbc->classify($words, $row, $offset);

	var_dump($result);
	echo PHP_EOL;
}
else {
	die('No arguments passed.'.PHP_EOL);
}

$_end = microtime(TRUE);
echo 	"Memory Usage: ", memory_get_usage(TRUE)/1024, " KB", PHP_EOL,
	"TIME Spent: ", ($_end - $_start), " seconds", PHP_EOL, PHP_EOL;
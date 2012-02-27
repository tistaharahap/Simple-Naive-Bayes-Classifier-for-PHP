<?php
/**
 * Abstract implementation of NaiveBayesClassifierStore for MySQL
 * 
 * @package	Simple NaiveBayesClassifier for PHP
 * @subpackage	NaiveBayesClassifierStoreMySQL
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

require_once 'NaiveBayesClassifierStore.php';

class NaiveBayesClassifierStoreMySQL extends NaiveBayesClassifierStore {
	
	private $db_host;
	private $db_port;
	private $db_name;
	private $db_user;
	private $db_pass;
	
	protected static $conn;
	
	private $trainerTable = 'trains';
	private $blacklistTable = 'blacklists';
	
	function __construct($conf = array()) {
		if(empty($conf))
			throw new NaiveBayesClassifierException(3001);
		if(empty($conf['db_host']))
			throw new NaiveBayesClassifierException(3101);
		if(empty($conf['db_port']))
			throw new NaiveBayesClassifierException(3102);
		if(empty($conf['db_name']))
			throw new NaiveBayesClassifierException(3103);
		if(empty($conf['db_user']))
			throw new NaiveBayesClassifierException(3104);
		if(empty($conf['db_pass']) && $conf['db_pass'] !== '')
			throw new NaiveBayesClassifierException(3105);
			
		$this->conn = isset($conf['db_persist']) && $conf['db_persist'] === TRUE ?
			mysql_pconnect("{$conf['db_host']}:{$conf['db_port']}", $conf['db_user'], $conf['db_pass']):
			mysql_connect("{$conf['db_host']}:{$conf['db_port']}", $conf['db_user'], $conf['db_pass']);
		if(!$this->conn)
			throw new NaiveBayesClassifierException(3106);
		else {
			if(!mysql_select_db($conf['db_name'])) {
				throw new NaiveBayesClassifierException(3107);
			}
		}
	}
	
	public function trainTo($words, $set) {
		if(!$this->isBlacklisted($words)) {
			$sql = "
				INSERT IGNORE INTO 
					{$this->trainerTable}
						(train_words, train_set)
					VALUES
						('{$words}', '{$set}')";
			$res = $this->_exec($sql);
		}
	}
	
	public function getAllSets() {
		$sql = "SELECT DISTINCT train_set FROM {$this->trainerTable}";
		$res = mysql_query($sql, $this->conn);
		$ret = array();
		while($row = mysql_fetch_array($res)) {
			$ret[] = $row['train_set'];
		}
		return $ret;
	}
	
	public function getWordCount($word) {
		$sql = "SELECT COUNT(*) as total FROM {$this->trainerTable} WHERE train_words = '{$word}'";
		$res = mysql_fetch_array(mysql_query($sql, $this->conn));
		return $res['total'];
	}
	
	public function getAllWordsCount() {
		$sql = "SELECT COUNT(*) as total FROM {$this->trainerTable}";
		$res = mysql_fetch_array(mysql_query($sql, $this->conn));
		return $res['total'];
	}
	
	public function getSetWordCount($set) {
		$sql = "SELECT COUNT(*) AS total FROM {$this->trainerTable} WHERE train_set = '{$set}'";
		$res = mysql_fetch_array(mysql_query($sql, $this->conn));
		return $res['total'];
	}
	
	public function getWordCountFromSet($word, $set) {
		$sql = "SELECT COUNT(*) AS total FROM {$this->trainerTable} WHERE train_words = '{$word}' AND train_set = '{$set}'";
		$res = mysql_fetch_array(mysql_query($sql, $this->conn));
		if($res['total'] == 0)
			return FALSE;
		return $res['total'];
	}
	
	public function getAllSetsWordCount() {
		$sql = "SELECT COUNT(*) AS total FROM {$this->trainerTable}";
		$res = mysql_fetch_array(mysql_query($sql, $this->conn));
		return $res['total'];
	}
	
	public function isBlacklisted($word) {
		$sql = "SELECT COUNT(*) AS total FROM {$this->blacklistTable} WHERE word = '{$word}'";
		$res = mysql_fetch_array(mysql_query($sql, $this->conn));
		return $res['total'] > 0 ? TRUE : FALSE;
	}
	
	private function _exec($sql) {
		return mysql_query($sql, $this->conn) or die(mysql_error());
	}
	
}
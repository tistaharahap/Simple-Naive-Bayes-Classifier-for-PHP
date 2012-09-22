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
	protected static $hsock;
	protected static $hsock_read;
	protected static $hsock_write;
	
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
		
		// MySQL connection	
        self::$conn = isset($conf['db_persist']) && $conf['db_persist'] === TRUE ?
			mysql_pconnect("{$conf['db_host']}:{$conf['db_port']}", $conf['db_user'], $conf['db_pass']):
			mysql_connect("{$conf['db_host']}:{$conf['db_port']}", $conf['db_user'], $conf['db_pass']);
		if(!self::$conn)
			throw new NaiveBayesClassifierException(3106);
		else {
			if(!mysql_select_db($conf['db_name'])) {
				throw new NaiveBayesClassifierException(3107);
			}
		}
		$this->db_name = $conf['db_name'];
		$this->db_host = $conf['db_host'];
		$this->db_port = $conf['db_port'];
		$this->db_user = $conf['db_user'];
		$this->db_pass = $conf['db_pass'];
		
		// HandlerSocket connection
        self::$hsock_read = $conf['hsock_read'];
        self::$hsock_write = $conf['hsock_write'];
		if($conf['hsock_read'] === TRUE || $conf['hsock_write'] === TRUE) {
			if(class_exists('HandlerSocket')) {
				$this->hsock->read = $conf['hsock_read'] === TRUE ? 
					new HandlerSocket($conf['db_host'], $conf['hsock']['db_port_read']) : 
					NULL;
				
				$this->hsock->write = $conf['hsock_write'] === TRUE ?
					new HandlerSocket($conf['db_host'], $conf['hsock']['db_port_write']) :
					NULL;
			} else {
				throw new NaiveBayesClassifierException(3200);
			}
		}
	}
	
	public function trainTo($words, $set) {
		$words = mysql_escape_string($words);
		$set = mysql_escape_string($set);
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
		if(!self::$hsock_read) {
			$sql = "SELECT DISTINCT train_set FROM {$this->trainerTable}";
			$res = mysql_query($sql, self::$conn);
			$ret = array();
			while($row = mysql_fetch_array($res)) {
				$ret[] = $row['train_set'];
			}
			return $ret;
		}
		else {
			if($this->_openIndex(3, $this->trainerTable, 'train_set', array('train_set'), TRUE) === TRUE) {
				$res = $this->hsock->read->executeSingle(3, '>', array(''), -1);
				$ret = array();
				foreach($res as $s) {
					if(!in_array($s[0], $ret))
						$ret[] = $s[0];
				}
				return $ret;
			}
		}
	}
	
	public function getWordCount($word) {
		if(!self::$hsock_read) {
			$sql = "SELECT COUNT(*) as total FROM {$this->trainerTable} WHERE train_words = '{$word}'";
			$res = mysql_fetch_array(mysql_query($sql, self::$conn));
			return $res['total'];
		}
		else {
			if($this->_openIndex(2, $this->trainerTable, 'train_words', array('train_words','train_set'), TRUE) === TRUE) {
				$res = $this->hsock->read->executeSingle(2, '=', array($word), -1, 0);
				return count($res);
			}
		}
	}
	
	public function getAllWordsCount() {
		if(!self::$hsock_read) {
			$sql = "SELECT COUNT(*) as total FROM {$this->trainerTable}";
			$res = mysql_fetch_array(mysql_query($sql, self::$conn));
			return $res['total'];
		}
		else {
			if($this->_openIndex(11111, $this->trainerTable, 'words_set', array('train_words','train_set'), TRUE) === TRUE) {
				$res = $this->hsock->read->executeSingle(11111, '>', array(''), -1, 0);
				return count($res);
			}
		}
	}
	
	public function getSetWordCount($set) {
		if(!self::$hsock_read) {
			$sql = "SELECT COUNT(*) AS total FROM {$this->trainerTable} WHERE train_set = '{$set}'";
			$res = @mysql_fetch_array(mysql_query($sql, self::$conn));
			return !empty($res['total']) ? $res['total'] : 0;
		}
		else {
			if($this->_openIndex(1111, $this->trainerTable, 'train_set', array('train_words','train_set'), TRUE) === TRUE) {
				$ret = $this->hsock->read->executeSingle(
					1111,
					'=',
					array($set),
					-1,
					0
				);
				return count($ret);
			}
		}
	}
	
	public function getWordCountFromSet($word, $set) {
		if(!self::$hsock_read) {
			$sql = "SELECT COUNT(*) AS total FROM {$this->trainerTable} WHERE train_words = '{$word}' AND train_set = '{$set}'";
			$res = @mysql_fetch_array(mysql_query($sql, self::$conn));
			if(empty($res['total']) || $res['total'] == 0)
				return (int) 0;
			return $res['total'];
		}
		else {
			if($this->_openIndex(111, $this->trainerTable, 'words_set', array('train_words','train_set'), TRUE) === TRUE) {
				$ret = $this->hsock->read->executeSingle(
					111,
					'=',
					array($word),
					-1,
					0
				);
				$count = 0;
				if(!empty($ret)) {
					foreach($ret as $r) {
						if($r[1] === $set)
							$count++;
					}
				}
				return $count;
			}
		}
	}
	
	public function getAllSetsWordCount() {
		if(!self::$hsock_read) {
			$sql = "SELECT COUNT(*) AS total FROM {$this->trainerTable}";
			$res = mysql_fetch_array(mysql_query($sql, self::$conn));
			return $res['total'];
		}
		else {
			if($this->_openIndex(11, $this->trainerTable, 'train_words', array('train_words'), TRUE) === TRUE) {
				$ret = $this->hsock->read->executeSingle(11, '>', array(''), -1);
				return count($ret);
			}
		}
	}
	
	public function isBlacklisted($word) {
		if(!self::$hsock_read) {
			$sql = "SELECT COUNT(*) AS total FROM {$this->blacklistTable} WHERE word = '{$word}'";
			$res = @mysql_fetch_array(mysql_query($sql, self::$conn));
			return !empty($res) && $res['total'] > 0 ? TRUE : FALSE;
		} else {
			if($this->_openIndex(1, $this->blacklistTable, 'PRIMARY', array('word'), TRUE) === TRUE) {
				$ret = $this->hsock->read->executeSingle(1, '=', array($word), 1, 0);
				return !empty($ret[0][0]) && $ret[0][0] === $word;
			}
			else {
				throw new NaiveBayesClassifierException(3201);
			}
		}
	}
	
	private function _openIndex($indexId, $tableName, $indexName, $tableFields, $read = TRUE) {
		$tableFields = implode(",", $tableFields);
		return $read === TRUE ?
			$this->hsock->read->openIndex($indexId, $this->db_name, $tableName, $indexName, $tableFields) :
			$this->hsock->write->openIndex($indexId, $this->db_name, $tableName, $indexName, $tableFields);
	}
	
	private function _exec($sql) {
		return mysql_query($sql, self::$conn) or die(mysql_error());
	}
	
}
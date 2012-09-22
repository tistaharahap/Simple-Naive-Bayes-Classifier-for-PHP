<?php
/**
 * Abstract implementation of NaiveBayesClassifierStore for MySQL
 * 
 * @package	Simple NaiveBayesClassifier for PHP
 * @subpackage	NaiveBayesClassifierStoreMongoDB
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

class NaiveBayesClassifierStoreMongoDB extends NaiveBayesClassifierStore {
	
	private $db_host;
	private $db_port;
	
	private $conn;
	private $db;
	
	private $trainer = 'trains';
	private $blacklist = 'blacklists';
	
	function __construct($conf = array()) {
		if(empty($conf))
			throw new NaiveBayesClassifierException(3001);
		if(empty($conf['db_host']))
			throw new NaiveBayesClassifierException(3101);
		if(empty($conf['db_port']))
			throw new NaiveBayesClassifierException(3102);
		if(empty($conf['db_name']))
			throw new NaiveBayesClassifierException(3103);
				
		// MongoDB connection	
        $this->conn = empty($conf['db_socket']) ?
        	new Mongo("{$conf['db_host']}:{$conf['db_port']}") :
        	new Mongo("{$conf['db_socket']}");
        $this->db = $this->conn->selectDB($conf['db_name']);
	}
	
	public function close() {
		$this->conn->close();
	}
	
	public function isBlacklisted($word) {
		$coll = $this->blacklist;
		$coll = $this->db->$coll;
		$criteria = array(
			'word'	=> $word
		);
		$cursor = $coll->find($criteria);
		return $cursor->count() == 0 ? FALSE : TRUE;
	}
	
	public function trainTo($word, $set) {
		$data = array(
			'word'	=> $word,
			'set'	=> $set
		);
		$coll = $this->trainer;
		$coll = $this->db->trains->insert($data);
	}
	
	public function getAllSets() {
		$coll = $this->trainer;
		$coll = $this->db->$coll;
		$fields = array(
			'set'
		);
		$cursor = $coll->find(array(), $fields);
		$ret = array();
		foreach($cursor as $c) {
			$ret[] = $c['set'];
		}
		return $ret;
	}
	
	public function getWordCount($word) {
		$coll = $this->trainer;
		$coll = $this->db->$coll;
		
		$criteria = array(
			'word'	=> $word
		);
		$cursor = $coll->find($criteria);
		return $cursor->count();
	}
	
	public function getAllWordsCount() {
		$coll = $this->trainer;
		$coll = $this->db->$coll;
		$cursor = $coll->find();
		return $cursor->count();
	}
	
	public function getSetWordCount($set) {
		$coll = $this->trainer;
		$coll = $this->db->$coll;
		
		$criteria = array(
			'set'	=> $set
		);
		$cursor = $coll->find($criteria);
		return $cursor->count();
	}
	
	public function getWordCountFromSet($word, $set) {
		$coll = $this->trainer;
		$coll = $this->db->$coll;
		
		$criteria = array(
			'word'	=> $word,
			'set'	=> $set
		);
		$cursor = $coll->find($criteria);
		return $cursor->count();
	}
	
	public function getAllSetsWordCount() {
		$coll = $this->trainer;
		$coll = $this->db->$coll;
		$cursor = $coll->find();
		return $cursor->count();
	}
	
}
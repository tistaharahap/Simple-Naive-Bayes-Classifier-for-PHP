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

class NaiveBayesClassifierStoreRedis extends NaiveBayesClassifierStore {
	
	private $conn;
	
	private $trainer = 'nbc-trains';
	private $blacklist = 'nbc-blacklists';
	private $setPrefix = "nbc-set";
	private $words = "nbc-words";
	private $sets = "nbc-sets";
	
	function __construct($conf = array()) {
		if(empty($conf))
			throw new NaiveBayesClassifierException(3001);
		if(empty($conf['db_host']))
			throw new NaiveBayesClassifierException(3101);
		if(empty($conf['db_port']))
			throw new NaiveBayesClassifierException(3102);
				
		// Redis connection	
        $this->conn = new Redis();
        $this->conn->connect($conf['db_host'], $conf['db_port']);
		$this->conn->select(77);
	}
	
	public function close() {
		$this->conn->close();
	}
	
	public function isBlacklisted($word) {
		$vals = $this->conn->lGetRange($this->blacklist, 0, -1);
		if(in_array($word, $vals))
			return TRUE;
			
		return FALSE;
	}
	
	public function trainTo($word, $set) {
		$this->conn->lPush($this->words, $word);
		$this->conn->sAdd($this->sets, $set);
		$this->conn->lPush("{$this->setPrefix}#{$set}", $word);
	}
	
	public function getAllSets() {
		return $this->conn->sMembers($this->sets);
	}
	
	public function getWordCount($word) {
		$vals = $this->conn->lGetRange($this->words, 0, -1);
		$count = 0;
		foreach($vals as $v) {
			if($v == $word)
				$count++;
		}
			
		return $count;
	}
	
	public function getAllWordsCount() {
		return $this->conn->lSize($this->words);
	}
	
	public function getSetWordCount($set) {
		return $this->conn->lSize("{$this->setPrefix}#{$set}");
	}
	
	public function getWordCountFromSet($word, $set) {
		$members = $this->conn->lGetRange("{$this->setPrefix}#{$set}", 0, -1);
		$count = 0;
		foreach($members as $m) {
			if($m == $word)
				$count++;
		}
			
		return $count;
	}
	
	public function getAllSetsWordCount() {
		$this->getAllWordsCount();
	}
	
}
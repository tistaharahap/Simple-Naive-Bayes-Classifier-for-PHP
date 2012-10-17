<?php
/**
 * Abstract implementation of NaiveBayesClassifierStore for Redis
 * 
 * @package	Simple NaiveBayesClassifier for PHP
 * @subpackage	NaiveBayesClassifierStoreRedis
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

	private $namespace	= 'nbc-ns';
	private $blacklist 	= 'nbc-blacklists';
	private $words 		= "nbc-words";
	private $sets 		= "nbc-sets";
	private $cache		= "nbc-cache";
	public $delimiter	= "_--%%--_";
	private $wordCount	= "--count--";
	
	function __construct($conf = array()) {
		if(empty($conf))
			throw new NaiveBayesClassifierException(3001);
		if(empty($conf['db_host']))
			throw new NaiveBayesClassifierException(3101);
		if(empty($conf['db_port']))
			throw new NaiveBayesClassifierException(3102);
		if(!empty($conf['namespace']))
			$this->namespace = $conf['namespace'];

		// Namespacing
		$this->blacklist	= "{$this->namespace}-{$this->blacklist}";
		$this->words		= "{$this->namespace}-{$this->words}";
		$this->sets			= "{$this->namespace}-{$this->sets}";
		$this->cache		= "{$this->namespace}-{$this->cache}";
				
		// Redis connection	
        $this->conn = new Redis();
        $this->conn->connect($conf['db_host'], $conf['db_port']);
		$this->conn->select(77);
	}
	
	public function close() {
		$this->conn->close();
	}
	
	public function addToBlacklist($word) {
		return $this->conn->incr("{$this->blacklist}#{$word}");
	}
	
	public function removeFromBlacklist($word) {
		return $this->conn->set("{$this->blacklist}#{$word}", 0);
	}
	
	public function isBlacklisted($word) {
		$res = $this->conn->get("{$this->blacklist}#{$word}");
		return !empty($res) && $res > 0 ? TRUE : FALSE;
	}
	
	public function trainTo($word, $set) {
		// Words
		$this->conn->hIncrBy($this->words, $word, 1);
		$this->conn->hIncrBy($this->words, $this->wordCount, 1);

		// Sets
		$key = "{$word}{$this->delimiter}{$set}";
		$this->conn->hIncrBy($this->words, $key, 1);
		$this->conn->hIncrBy($this->sets, $set, 1);
	}

	public function deTrainFromSet($word, $set) {
		$key = "{$word}{$this->delimiter}{$set}";

		$check = $this->conn->hExists($this->words, $word) &&
			$this->conn->hExists($this->words, $this->wordCount) &&
			$this->conn->hExists($this->words, $key) &&
			$this->conn->hExists($this->sets, $set);

		if($check) {
			// Words
			$this->conn->hIncrBy($this->words, $word, -1);
			$this->conn->hIncrBy($this->words, $this->wordCount, -1);

			// Sets
			$this->conn->hIncrBy($this->words, $key, -1);
			$this->conn->hIncrBy($this->sets, $set, -1);

			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function getAllSets() {
		return $this->conn->hKeys($this->sets);
	}
	
	public function getSetCount() {
		return $this->conn->hLen($this->sets);
	}
	
	public function getWordCount($words) {
		return $this->conn->hMGet($this->words, $words);
	}
	
	public function getAllWordsCount() {
		return $this->conn->hGet($this->wordCount, $this->wordCount);
	}
	
	public function getSetWordCount($sets) {
		return $this->conn->hMGet($this->sets, $sets);
	}
	
	public function getWordCountFromSet($words, $sets) {
		$keys = array();
		foreach($words as $word) {
			foreach($sets as $set) {
				$keys[] = "{$word}{$this->delimiter}{$set}";
			}
		}
		return $this->conn->hMGet($this->words, $keys);
	}
	
}
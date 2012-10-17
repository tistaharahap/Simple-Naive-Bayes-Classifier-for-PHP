<?php
/**
 * Main Class
 * 
 * @package	Simple NaiveBayesClassifier for PHP
 * @subpackage	NaiveBayesClassifier
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

require_once 'NaiveBayesClassifierException.php';

class NaiveBayesClassifier {
	
	private $store;
	private $debug = TRUE;
	
	public function __construct($conf = array()) {
		if(empty($conf))
			throw new NaiveBayesClassifierException(1001);
		if(empty($conf['store']))
			throw new NaiveBayesClassifierException(1002);
		if(empty($conf['store']['mode']))
			throw new NaiveBayesClassifierException(1003);
		if(empty($conf['store']['db']))
			throw new NaiveBayesClassifierException(1004);
			
		if(!empty($conf['debug']) && $conf['debug'] === TRUE)
			$this->debug = TRUE;
			
		switch($conf['store']['mode']) {
			case 'redis':
				require_once 'NaiveBayesClassifierStoreRedis.php';
				$this->store = new NaiveBayesClassifierStoreRedis($conf['store']['db']);
				break;
		}
	}
	
	public function train($words, $set) {
		$words = $this->cleanKeywords(explode(" ", $words));
		foreach($words as $w) {
			$this->store->trainTo(html_entity_decode($w), $set);
		}
	}

	public function deTrain($words, $set) {
		$words = $this->cleanKeywords(explode(" ", $words));
		foreach($words as $w) {
			$this->store->deTrainFromSet(html_entity_decode($w), $set);
		}
	}

	public function classify($words, $count = 10, $offset = 0) {
		$P = array();
		$score = array();

		// Break keywords
		$keywords = $this->cleanKeywords(explode(" ", $words));

		// All sets
		$sets = $this->store->getAllSets();
		$P['sets'] = array();

		// Word counts in sets
		$setWordCounts = $this->store->getSetWordCount($sets);
		$wordCountFromSet = $this->store->getWordCountFromSet($keywords, $sets);

		foreach($sets as $set) {
			foreach($keywords as $word) {
				$key = "{$word}{$this->store->delimiter}{$set}";
				if($wordCountFromSet[$key] > 0)
					$P['sets'][$set] += $wordCountFromSet[$key] / $setWordCounts[$set];
			}

			if(!is_infinite($P['sets'][$set]) && $P['sets'][$set] > 0)
				$score[$set] = $P['sets'][$set];
		}

		arsort($score);

		return array_slice($score, $offset, $count-1);
	}
	
	public function blacklist($words = array()) {
		$clean = array();
		if(is_string($words)) {
			$clean = array($words);
		}
		else if(is_array($words)) {
			$clean = $words;
		}
		$clean = $this->cleanKeywords($clean);
		
		foreach($clean as $word) {
			$this->store->addToBlacklist($word);
		}
	}

	private function cleanKeywords($kw = array()) {
		if(!empty($kw)) {
			$ret = array();
			foreach($kw as $k) {
				$k = strtolower($k);
				$k = preg_replace("/[^a-z]/i", "", $k);

				if(!empty($k) && strlen($k) > 2) {
					$k = strtolower($k);
					if(!empty($k))
						$ret[] = $k;
				}
			}
			return $ret;
		}
	}
	
	private function isBlacklisted($word) {
		return $this->store->isBlacklisted($word);
	}
	
	private function _debug($msg) {
		if($this->debug)
			echo $msg . PHP_EOL;
	}
	
}

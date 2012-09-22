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
			case 'mysql':
				require_once 'NaiveBayesClassifierStoreMySQL.php';
				$this->store = new NaiveBayesClassifierStoreMySQL($conf['store']['db']);
				break;
			case 'mongodb':
				require_once 'NaiveBayesClassifierStoreMongoDB.php';
				$this->store = new NaiveBayesClassifierStoreMongoDB($conf['store']['db']);
				break;
		}
	}
	
	public function train($words, $set) {
		$words = $this->cleanKeywords(explode(" ", $words));
		foreach($words as $w) {
			$this->store->trainTo(html_entity_decode($w), $set);
		}
	}
	
	public function classify($words, $count = 10) {
		$keywords = $this->cleanKeywords(explode(" ", $words));
		$keywordsCount = count($keywords);
		
		$score = array();
		$P = array();
		
		// Probability of each keyword towards the whole set P(keyword)
		$P['kws-sum'] = 0;
		foreach($keywords as $kw) {
			$P['kws-sum'] += $this->store->getWordCount($kw);
		}
		$P['kws-sum'] = $P['kws-sum'] > 0 ? log($P['kws-sum']) : 0;
		
		if($P['kws-sum'] != 0) {
			$sets = $this->store->getAllSets();
			$sets = array_unique($sets);
			
			$numberOfSets = 0;
			foreach($sets as $s) {
				$numberOfSets++;
			}
			
			// Probability of the current set winning P(set)
			$P['set'] = log(1 / $numberOfSets);
			
			foreach($sets as $set) {
				// Set Word Count
				$setWordCount = $this->store->getSetWordCount($set);
				
				foreach($keywords as $kw) {
					// Probability of the current keyword belonging to the current set P(keyword|set)
					$keywordInSetCount = $this->store->getWordCountFromSet($kw, $set);
					if($keywordInSetCount > 0) {
						$add = $keywordInSetCount == 0 ? 0 : log($keywordInSetCount / $setWordCount);
						$P[$set] += $add;
					}
				}
				
				$P['top'] = $P[$set] + $P['set'];
				$P['bottom'] = $P['kws-sum'];
				$P[$set] = log(abs($P['top'] / $P['bottom']));
				
				$score[$set] = $P[$set];
			}
		}
		
		arsort($score);
		
		return array_slice($score, 0, $count-1);
	}
	
	private function cleanKeywords($kw = array()) {
		if(!empty($kw)) {
			$ret = array();
			foreach($kw as $k)
				if(!$this->isBlacklisted($k)) {
					$k = preg_replace("/[^0-9a-z]/i", "", $k);
					$k = strtolower($k);
					if(!empty($k))
						$ret[] = $k;
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

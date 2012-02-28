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
	private $debug = FALSE;
	
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
		}
	}
	
	public function train($words, $set) {
		$words = $this->cleanKeywords(explode(" ", $words));
		foreach($words as $w)
			$this->store->trainTo(html_entity_decode($w), $set);
	}
	
	public function classify($words) {
		$kw = $this->cleanKeywords(explode(" ", $words));
		if($this->debug) {
			echo "Keywords: ", PHP_EOL;
			print_r($kw); echo PHP_EOL;
		}
		
		$sets = $this->store->getAllSets();
		if($this->debug) {
			echo "Sets: ", PHP_EOL;
			print_r($sets); echo PHP_EOL;
		}
		
		$P = array();
		$p = array();
		foreach($sets as $s) {
			if($this->debug) {
				echo "For {$s}: ", PHP_EOL;
			}
			
			$P[$s]['top'] = $P[$s]['bottom'] = 1;
			
			// P(set1)
			$p[$s]['set'] = $this->store->getSetWordCount($s) / $this->store->getAllSetsWordCount();
			if($this->debug) {
				echo "P({$s}): ", $p[$s]['set'], PHP_EOL;
			}
			
			foreach($kw as $k) {
				// P(kw[n])
				$p[$s]['kw'][$k] = $this->store->getWordCount($k) / $this->store->getAllWordsCount();
				if($this->debug) {
					echo "P({$k}): ", $p[$s]['kw'][$k], PHP_EOL;
				}

				// P(kw[n]|set[n])
				$wcs = $this->store->getWordCountFromSet($k, $s);
				if($wcs > 0) {
					$p[$s]['kw-set'][$k] = $this->store->getWordCount($k) / $wcs;
					//if($p[$s]['kw-set'][$k] > 1)
					//	$p[$s]['kw-set'][$k] = 1;
				}
				else
					$p[$s]['kw-set'][$k] = 0.1;
				if($this->debug) {
					echo "P({$k}|{$s}): ", $p[$s]['kw-set'][$k], PHP_EOL;
				}
				
				// Formula
				$P[$s]['top'] = $P[$s]['top'] * $p[$s]['kw-set'][$k];
				$P[$s]['bottom'] = $P[$s]['bottom'] * $p[$s]['kw'][$k];
			}
			
			$P[$s]['top'] = $P[$s]['top'] * $p[$s]['set'];
			
			$P[$s]['bottom'] = $P[$s]['bottom'] > 0 ? $P[$s]['bottom'] : 0.1;
			
			$P[$s]['conclusion'] = $P[$s]['top'] / $P[$s]['bottom'];
			if($this->debug) {
				echo "P({$s}|";
				$ks = "";
				foreach($kw as $k)
					$ks .= $k.",";
				echo rtrim($ks, ","), "): ", number_format($P[$s]['conclusion'], 10, ',', '.'), PHP_EOL;
			}
			
			echo PHP_EOL;
		}
	}
	
	private function cleanKeywords($kw = array()) {
		if(!empty($kw)) {
			$ret = array();
			foreach($kw as $k)
				if(!$this->isBlacklisted($k)) {
					$k = preg_replace("/[^0-9a-z]/i", "", $k);
					$k = strtolower($k);
					$ret[] = $k;
				}
			return $ret;
		}
	}
	
	private function isBlacklisted($word) {
		return $this->store->isBlacklisted($word);
	}
	
}
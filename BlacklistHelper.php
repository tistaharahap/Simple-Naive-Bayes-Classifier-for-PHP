<?php

class BlacklistHelper {

	private $conn;
	private $namespace = 'nbc-ns';

	function __construct($conf) {
		if(empty($conf))
			throw new NaiveBayesClassifierException(3001);
		if(empty($conf['db_host']))
			throw new NaiveBayesClassifierException(3101);
		if(empty($conf['db_port']))
			throw new NaiveBayesClassifierException(3102);
		if(!empty($conf['namespace']))
			$this->namespace = $conf['namespace'];

		// Redis connection
		$this->conn = new Redis();
		$this->conn->connect($conf['db_host'], $conf['db_port']);
		$this->conn->select(77);
	}

	public function generateStopwords($data) {
		if(!is_array($data)) {
			throw new Exception("Parameter must be an array");
		}

		$key = "{$this->namespace}-blacklist-temp";
		foreach($data as $item) {
			$words = $this->cleanKeywords(explode(" ", $item));
			foreach($words as $word) {
				$this->conn->zIncrBy($key, 1, $word);
			}
		}

		return $this->conn->zSize($key);
	}

	public function getRange($offset = 0, $row = 100, $minCount = 0, $maxCount = INF) {
		$key = "{$this->namespace}-blacklist-temp";
		return $this->conn->zRevRangeByScore($key, $maxCount, $minCount, array(
			'withscores' => TRUE,
			'limit' => array($offset, $row)
		));
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

}
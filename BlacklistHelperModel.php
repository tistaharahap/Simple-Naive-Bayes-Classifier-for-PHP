<?php

require_once 'BlacklistHelper.php';

$stopwords = new BlacklistHelper(array(
	'db_host'	=> '127.0.0.1',
	'db_port'	=> '6379',
	'namespace'	=> 'reviews'
));

/*$data = BlacklistHelperModel::getData();
if(!empty($data)) {
	$blacklist = $stopwords->generateStopwords($data);
	var_dump($blacklist);
}*/

$blacklist = $stopwords->getRange(0, 50);
var_dump($blacklist);

class BlacklistHelperModel {

	public static function getData() {
		$db = mysql_connect('127.0.0.1', 'root', '');
		mysql_select_db('bayes') or die('Cannot select DB');
		$sql = "SELECT review_text FROM reviews";
		$q = mysql_query($sql);

		$temp = array();
		while($row = mysql_fetch_object($q)) {
			$temp[] = $row->review_text;
		}

		mysql_close($db);
		return $temp;
	}

}
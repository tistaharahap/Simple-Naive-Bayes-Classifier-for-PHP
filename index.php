<?php
/**
 * CLI Implementation for NaiveBayesClassifier project
 * 
 * @package	Simple NaiveBayesClassifier for PHP
 * @subpackage	CLI Runner - Implementation
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

require_once 'NaiveBayesClassifier.php';

$_start = microtime(TRUE);

$nbc = new NaiveBayesClassifier(array(
	'store' => array(
		'mode'	=> 'mysql',
		'db'	=> array(
			'db_host'	=> 'localhost',
			'db_port'	=> '3306',
			'db_name'	=> 'bayes',
			'db_user'	=> 'root',
			'db_pass'	=> '',
			'db_persist'	=> FALSE,
			'hsock_read'	=> TRUE,
			'hsock_write'	=> FALSE,
			'hsock'		=> array(
				'db_port_read'	=> 9998,
				'db_port_write'	=> 9999
			)
		)
	),
	'debug' => TRUE
));

$nbc->train('Solskjaer Masih Ogah Nglatih di Inggris. Mantan bintang Manchester United, Ole Gunnar Solskjaer menyatakan dirinya belum ingin kembali ke Inggris untuk menangani tim. Saat ini Solskjaer masih nyaman melatih klub asal negaranya Norwegia, Molde walau namanya sempat dikaitkan sebagai salah satu kandidat pelatih Blackburn Rovers yang sedang terpuruk di Liga Premier. Solskjaer sendiri mengakui bahwa dirinya telah mendapatkan beberapa tawaran dari', 'manchester united');
$nbc->train('Soal Pogba, MU Angkat Tangan. Manchester United disebutkan telah angkat tangan untuk membahas kontrak baru pemain mudanya, Paul Pogba. Seperti yang diberitakan The Sun, pemain asal Prancis itu dalam perjalanan keluar dari Old Trafford diakhir musim nanti setelah kembali menolak tawaran kontrak baru yang diajukan klub. Sir Alex Ferguson sebenarnya sangat berharap pemain berusia 18 tahun itu bertahan karena memiliki', 'manchester united');
$nbc->train('Istri Wayne Rooney Bangga Jadi Fans Liverpool. Sikap Coleen Rooney itu mendapat kecaman dari fans Manchester United', 'manchester united');
$nbc->train('Rooney: Fergie Sosok Pemimpin Luar Biasa. Striker Manchester United, Wayne Rooney, memuji sosok pelatih Sir Alex Ferguson sebagai manajer hebat yang memberi inspirasi untuk setiap pemain di Old Trafford.', 'manchester united');
$nbc->train('Tak Ada Ruang untuk Kesalahan, Setan Merah. Dengan perburuan gelar dengan Manchester City yang semakin seru, Manchester United dituntut untuk fokus seratus persen. Tak ada ruang untuk membuat kesalahan bagi juara musim lalu itu.', 'manchester united');
$nbc->train('Scholes Yakin MU Bisa Juara. Gelandang veteran Paul Scholes yakin Manchester United bisa mempertahankan titel juara Liga Premiere Inggris. Meski saat ini MU masih tertinggal dua poin dari rival satu.', 'manchester united');
$nbc->train('Scholes: Jangan Ada Kesalahan di Sisa Laga. Gelandang veteran Manchester United, Paul Scholes, mengatakan bahwa timnya tak boleh melakukan sedikit pun kesalahan di sisa 12 laga Premier League musim ini.', 'manchester united');
$nbc->train('Guangzhou Minati Mantan Striker MU. Klub Liga Super China, Guangzhou Evergrande dilaporkan berminat mendatangkan mantan striker Manchester United, Danny Webber. Pemain berusia 30 tahun itu saat ini tanpa klub setelah dilepas Potsmounth musim panas tahun lalu. Namun minat Guangzhou tidaklah mudah karena mantan manajer QPR yang saat ini menangani Leeds United, Neil Warnock juga disebutkan berminat mendapatkan jasa pemain yang', 'manchester united');
$nbc->train('Fergie: Giggs dan Scholes, Pemain Terbaik United. Pelatih Manchester United, Sir Alex Ferguson memuji Ryan Giggs dan Paul Scholes setelah mereka berdua mencetak gol pada pertandingan melawan Norwich City pada hari Minggu lalu. Scholes berhasil mencetak gol pertama sebelum Giggs membuktikan diri menjadi pahlawan pada detik-detik terakhir', 'manchester united');

$nbc->train('Messi Tak Pikirkan Klub Lain. Mega bintang Argentina, Lionel Messi menegaskan soal masa depan dirinya hanya berpikir soal Barcelona. Bagi Messi, Barcelona adalah rumahnya sehingga dirinya sama sekali tidak berpikir soal kemungkinan berganti klub. Anda tidak pernah tahu bagaimana karir anda akan tetapi Barcelona adalah rumah saya dan saya tahu tidak ada tempat lain yang bisa seperti ini', 'barcelona');
$nbc->train('Cesc: Saya Tak Bujuk Van Persie ke Barca. Ada isu berkembang bahwa skipper Arsenal, Robin van Persie, akan pergi akhir musim nanti dari Emirates Stadium, kabarnya ia berminat ke Barcelona.', 'barcelona');
$nbc->train('Silva: Barcelona Menarik, Tapi', 'barcelona');
$nbc->train('Fabregas bantah bujuk RvP. Cesc Fabregas membantah berita dirinya tengah membujuk kapten Arsenal Robin Van Persie (RvP) untuk meninggalkan Arsenal dan bergabung dengan Barcelona.', 'barcelona');
$nbc->train('Fabregas Tak Akan Bujuk Van Persie ke Barca. Masih menunda kesepakatan kontrak baru hingga akhir musim, Robin Van Persie tterus dispekulasikan masa depannya di Arsenal. Tapi ia takkan dibujuk Cesc Fabregas untuk ke Barcelona.', 'barcelona');
$nbc->train('Silva: Setiap Pemain Bermimpi Berkostum Barca. Bek AC Milan Thiago Silva mengakui bahwa dia mengetahui ketertarikan Barcelona terhadap dirinya. Meski demikian, saat ini pemain internasional Brasil ini mengaku hanya ingin fokus dengan Rossoneri.', 'barcelona');
$nbc->train('Falcao Sebut Keputusan Wasit Untungkan Barca. Penyerang Ateltico Madrid, Falcao rupanya masih menyimpan rasa kesal terkait dengan kekalahan 2-1 melawan Barcelona akhir minggu lalu. Striker asal Kolombia ini mengkritik keras wasit yang dirasanya menguntungkan Barca.', 'barcelona');
$nbc->train('Thiago Silva Buka Opsi Gabung ke Barcelona. Dia memang masih ingin setia dengan AC Milan, namun juga menyadari menolak tim sebesar Barcelona adalah hal yang sangat sulit.', 'barcelona');
$nbc->train('Guardiola Ajukan Syarat Jika Direkrut Inter', 'barcelona');

$nbc->classify('liga juara');

$_end = microtime(TRUE);

echo 	"Memory Usage: ", memory_get_usage()/1024, " KB", PHP_EOL,
	"TIME Spent: ", ($_end - $_start), " seconds", PHP_EOL, PHP_EOL;
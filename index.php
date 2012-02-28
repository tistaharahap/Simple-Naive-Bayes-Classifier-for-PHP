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
			'db_persist'	=> FALSE
		)
	),
	'debug' => TRUE
));

$nbc->train('Gelandang veteran Paul Scholes yakin Manchester United bisa mempertahankan titel juara Liga Premiere Inggris. Meski saat ini MU masih tertinggal dua poin dari rival', 'tista');
$nbc->train('Gelandang veteran Manchester United, Paul Scholes, mengatakan bahwa timnya tak boleh melakukan sedikit pun kesalahan di sisa 12 laga Premier League musim ini', 'tista');
$nbc->train('Klub Liga Super China, Guangzhou Evergrande dilaporkan berminat mendatangkan mantan striker Manchester United, Danny Webber. Pemain berusia 30 tahun itu saat ini tanpa klub setelah dilepas Potsmounth musim panas tahun lalu. Namun minat Guangzhou tidaklah mudah karena mantan manajer QPR yang saat ini menangani Leeds United, Neil Warnock juga disebutkan berminat mendapatkan jasa pemain', 'tista');
$nbc->train('Pelatih Manchester United, Sir Alex Ferguson memuji Ryan Giggs dan Paul Scholes setelah mereka berdua mencetak gol pada pertandingan melawan Norwich City pada hari Minggu lalu. Scholes berhasil mencetak gol pertama sebelum Giggs membuktikan diri menjadi pahlawan pada detik-detik terakhir', 'tista');
$nbc->train('Rekor menakjubkan Ryan Giggs dengan 900 penampilan untuk Manchester United tidak akan bisa disamai, kata pelatih Alex Ferguson', 'tista');
$nbc->train('Beberapa klub Inggris dikabarkan sudah memantau sang pemain seperti Tottenham Hotspur, Manchester City, dan Newcastle United', 'tista');
$nbc->train('Manajer Manchester United Sir Alex Ferguson mengatakan prestasi Ryan Giggs dengan tampil 900 kali untuk klub itu tidak akan pernah terkalahkan', 'tista');
$nbc->train('Ryan Giggs tak kuasa menahan kebahagiannya usai mencetak gol kemenangan di laga spesialnya bersama Manchester United', 'tista');
$nbc->train('Sir Alex Ferguson memang memuji kegigihan anak-anak Manchester United saat menghadapi Norwich kemarin, namun ia juga tak malu menyebut timnya beruntung bisa pulang dengan tiga poin', 'tista');
$nbc->train('Dengan perburuan gelar dengan Manchester City yang semakin seru, Manchester United dituntut untuk fokus seratus persen. Tak ada ruang untuk membuat kesalahan bagi juara musim lalu itu.', 'tista');
$nbc->train('Ketika City dengan mudahnya menang atas Blackburn Rovers akhir pekan lalu, MU ditinggalkan pada satu pilihan: wajib menang atas Norwich City. Kewajiban itu nyaris tak bisa dicapai, namun pada akhirnya Ryan Giggs datang sebagai penyelamat di menit-menit akhir. Setan Merah menang 2-1.', 'tista');
$nbc->train('Dengan kemenangan itu, MU tetap menempel ketat sang rival sekota dengan jarak dua poin. Giggs pun memprediksi bahwa persaingan kedua klub bakal berlangsung sampai penghujung musim kompetisi.', 'tista');
$nbc->train('Seperti setuju dengan pendapat Giggs, Scholes juga menambahkan bahwa untuk tetap bersaing dengan City artinya MU tak boleh lenga lagi. Tak boleh ada poin yang terbuang percuma lagi.', 'tista');
$nbc->train('"Tak ada artinya bermain imbang, jadi kami mengirim semua orang ke depan untuk mencari gol itu, dan kami yakin bahwa kami akan melakukannya lagi," ujar Scholes kepada MUTV seraya menyinggung gol Giggs ke gawang Norwich.', 'tista');
$nbc->train('"Tentu saja tiga poin berbeda dari satu poin. Itulah yang ingin kami lakukan.". "Kami menyadari bahwa untuk menjuarai liga, mungkin kami harus memenangi semua laga. Tapi, kami siap dan sudah dipersiapkan untuk itu. Saya harap, kami bisa melakukannya," tegas Scholes.', 'tista');
$nbc->train('Manchester City dan Manchester United terus bersaing di papan atas klasemen Liga Primer Inggris. Meski City masih ada di puncak, asa Ryan Giggs belum pupus karena "peluit akhir" belum berbunyi. Akhir pekan ini City yang bermain sehari lebih dulu dari MU berhasil meraup poin penuh saat menjamu Blackburn Rovers. Di Etihad, Sabtu (25/2/2012), City menang 3-0. Hasil tersebut bikin City sudah melakoni 26 pertandingan dengan raihan 63 angka, yang merupakan jumlah terbaik sejauh ini. MU, sang juara bertahan, kemudian juga memetik kemenangan ketika melawat ke markas Norwich di Carrow Road, Minggu (26/2). Hasil itu membuat Setan Merah terus menguntit City dengan selisih dua angka. "Kami tahu betapa pentingnya hasil ini, kemarin City sudah memberikan tekanan untuk kami dan kami tahu harus menang," tegas Giggs yang menjadi penentu kemenangan MU lawan Norwich, seperti dikutip BBC. Kemenangan atas MU atas Norwich sendiri dipastikan di menit-menit akhir. MU sempat unggul lebih dulu lewat Paul Scholes, tetapi Norwich berhasil menyamakan skor pada menit 84. Beberapa saat menjelang berakhirnya pertandingan, Giggs menggebrak. Pemain veteran MU yang melakoni laga ke-900 untuk klubnya di partai tersebut mencetak gol telat di menit injury time. Untuk Giggs, gol itu merupakan perwujudan betapa MU takkan menyerah sampai dengan saat terakhir. Hal serupa ia tegaskan bakal diupayakan untuk menggulingkan City di akhir musim. "Aku yakin akan ada kejutan-kejutan lain dalam perebutan gelar juara dan mengharapkan adanya drama dan gol-gol telat," simpul pemain berusia 38 tahun tersebut.', 'tista');

$nbc->train('Arsenal Optimistis Van Persie Tidak Pindah. Dia membuktikan komitmen dengan gol-golnya di Inggris', 'arie');
$nbc->train('Arsene Wenger memuji kontribusi Theo Walcott, saat Arsenal menghancurkan Tottenham Hotspur dengan skor telak 5-2, Wenger menyebut anak asuhnya tersebut memiliki mental yang baja meski mendapat kritikan keras dari para fans', 'arie');
$nbc->train('Arsenal memang tak meraih prestasi membanggakan dalam kompetisi, baik di liga domestik maupun kejuaraan Eropa. Namun, mereka mencatatkan prestasi membanggakan dalam hal finansial klub', 'arie');
$nbc->train('Kiper Arsenal Wojciech Szczesny yakin bahwa kaptennya, Robin van Persie akan tetap bertahan di The Gunners pada musim depan. Sejauh ini, masa depan Van Persie di Emirates Stadium kerap menjadi bahan pertanyaan. Kontrak pemain asal Belanda tersebut akan habis', 'arie');
$nbc->train('Tottenham Hotspur baru saja ditelan kekalahan memalukan, 5-2 atas rival mereka di derby London Utara, Arsenal. Menanggapi hal demikian, Rafael van der Vaart mengungkapkan kekalahan tersebut bukan berarti bahwa timnya akan disalip oleh The Gunners. The Lillywhites masih menempati posisi... Read More &#187;SOURCE: Rafal Van Der Vaart: Posisi Ketiga Adalah Target Kami', 'arie');
$nbc->train('Arsenal mengumumkan kestabilan keuangan dalam setengah tahun terakhir. Namun demikian, mereka tidak menjanjikan transfer besar karena sejumlah alasan', 'arie');
$nbc->train('Pemain timnas Inggris, Kyle Walker harus absen membela tim Tiga Singa kala menjamu Belanda di Stadion Wembley, Rabu (29/2). Pasalnya, ia harus mengalami cedera engkel kala membela Tottenham Hotspur melawan Arsenal, pekan kemarin', 'arie');
$nbc->train('Kalah besar oleh Arsenal membuat poin Tottenham Hotspur di klasemen Premier League menjauhi duo Manchester yang sukses meraih kemenangan. Melihat konsidi tersebut Rafael van der Vaart menilai Spurs semakin jauh dari gelar juara liga musim ini', 'arie');
$nbc->train('Kemenangan besar Arsenal 5- 2 dalam laga bertajuk derby London Utara melawan Totttenham Hotspur pekan kemarin harus dibayar mahal dengan cederanya Tomas Rosicky dan Thomas Vermaelen. Keduanya diperkirakan akan absen dalam laga melawan Liverpool', 'arie');
$nbc->train('Keputusan Robin van Persie menunda pembicaraan perpanjangan kontrak dengan Arsenal memunculkan spekulasi dia akan pergi dari Emirates Stadium. Wojciech Szczesny yakin hal itu tak akan terjadi', 'arie');

$nbc->train('Kiper Juventus, Italia, Gianluigi Buffon mendapat kritik pedas dari komite wasit Italia (AIA). Hal ini terkait pernyataannya tentang laga melawan melawan AC Milan pada pekan kemarin', 'baga');
$nbc->train('Bek AC Milan, Philippe Mexes, resmi mendapatkan sanksi larangan bermain di tiga laga. Hal ini terkait aksi pemukulannya kepada pemain Juventus, Marco Boriello dalam laga pekan kemarin', 'baga');
$nbc->train('Michel Platini ikut angkat bicara mengenai komentar Gianluigi Buffon pasca pertandingan AC Milan kontra Juventus. Presiden UEFA yang juga mantan bintang Juve itu menilai komentar Buffon sudah kelewat batas', 'baga');
$nbc->train('Kiper Juventus, Gianluigi Buffon diungkapkan akan tetap menjadi kapten timnas Italy menyusul kejadian yang terjadi ketika timnya diimbangi AC Milan di laga lanjutan Liga Italy, Serie A akhir pekan lalu. Buffon sedang dihujani bada kritik tak mengenakan karena gol', 'baga');
$nbc->train('Juventus secepatnya kembali dengan AC Milan dalam ajang Coppa Italia musim ini. Pasalnya, tanggal laga leg kedua semi final Coppa Italia antara Juve dan Milan telah keluar', 'baga');
$nbc->train('Dia memang masih ingin setia dengan AC Milan, namun juga menyadari menolak tim sebesar Barcelona adalah hal yang sangat sulit', 'baga');
$nbc->train('Kiper Juventus Gianluigi Buffon mendapat kritik dari komite wasit Italia, atas komentarnya terkait hasil kontroversial di laga kontra AC Milan pada akhir pekan', 'baga');
$nbc->train('Pemain bertahan AC Milan Philippe Mexes mendapatkan hukuman larangan bermain sebanyak tiga pertandingan setelah kedapatan mengasari pemain Juventus Marco Boriello, dalam duel kedua tim akhir pekan lalu', 'baga');
$nbc->train('Bek AC Milan, Philippe Mexes telah terkena sanksi skorsing selama tiga laga karena memukul pemain Juventus, Marco Borriello di laga big match antara kedua raksasa Italia tersebut, akhir pekan lalu. Mexes tertangkap kamera, sengaja melakukan pukulan ke arah perut Boriello', 'baga');
$nbc->train('Pelatih Italia, Cesare Prandelli menegaskan bahwa kiper Gianluigi Buffon akan tetap mengenakan ban kapten timnas. Buffon sedang menjadi objek pembicaraan di Italia terkait aksinya menyelamatkan bola yang sebenarnya telah melewati garis gawang hasil kinerja Sulley Muntari ketika AC Milan bertemu Juventus hari Minggu kemarin. Buffon dianggap beberapa pihak tak pantas melakukan aksi itu karena merupakan', 'baga');

$nbc->train('Penyerang Atletico Madrid, Spanyol, Falcao rupanya masih menyimpan rasa kesal terkait dengan kekalahan 2-1 melawan Barcelona akhir minggu lalu. Striker asal Kolombia ini mengkritik keras wasit yang dirasanya menguntungkan Barca.', 'adit');
$nbc->train('Thiago Silva Buka Opsi Gabung ke Barcelona. Dia memang masih ingin setia dengan AC Milan, namun juga menyadari menolak tim sebesar Barcelona adalah hal yang sangat sulit.', 'adit');
$nbc->train('Guardiola Ajukan Syarat Jika Direkrut Inter', 'adit');
$nbc->train('Casillas Beri Atensi untuk Rayo Rayo layak mendapatkan kredit besar.Senin, 27 Februari 2012, 13:55 WIB Iker Casillas saat menghadapi Barcelona - Pertarungan sengit yang diperlihatkan Rayo Vallecano saat menghadapi Real Madrid mendapat atensi luas dari sang kapten, Iker Casillas. Ia mengacungkan jempol atas penampilan tim tuan rumah. Cassilas menilai, performa tim promosi itu pantas', 'adit');
$nbc->train('Kapten Barcelona Carles Puyol menyatakan bahwa timnya tidak akan menyerah dengan mudah dalam perebutan gelar La Liga musim ini.', 'adit');
$nbc->train('Bukti David Villa Peduli Kemansian Bantu UNICEF Brantas Gizi Buruk.', 'adit');
$nbc->train('Puyol Tolak Angkat Bendera Putih. Pelatih Pep Guardiola boleh saja menyerah dalam perburuan gelar juara La Liga Spanyol. Pasalnya, Barcelona sudah jauh ditinggalkan Real Madrid yang kini sendirian memimpin klasemen', 'adit');
$nbc->train('Rosell dan Guardiola Mulai Hambar. Presiden Barcelona, Sandro Rosell makin tidak nyaman soal masa depan Pep Guardiola sehubungan masih keenganan sang pelatih membicarakan kontrak barunya. Karena situasi ini El Confidencial mengabarkan bahwa hubungan Rosell dan Guardiola tidak sehangat sebelumnya karena khawatir kehilangan pelatih yang sangat dicintai fans Barca itu. Rosell merasa sangat frustasi karena merasa tidak lagi bisa menyakinkan Guardiola', 'adit');
$nbc->train('Pique Belum Berencana Nikahi Shakira. Bintang Barcelona, Gerard Pique, mengungkapkan tak ada masalah dalam hubungan dia dengan diva pop Kolombia, Shakira.', 'adit');
$nbc->train('Guardiola: Barca Takkan Juara. Nada pesimistis mulai keluar dari mulut Pep Guardiola terkait peluang Barcelona mempertahankan titel La Liga. Dengan jarak yang begitu jauh dari Real Madrid, dia menilai Barca sudah sangat sulit jadi juara.', 'adit');

$nbc->classify('manchester arsenal barcelona milan');

$_end = microtime(TRUE);

echo "TIME Spent: ", ($_end - $_start), " seconds", PHP_EOL, PHP_EOL;